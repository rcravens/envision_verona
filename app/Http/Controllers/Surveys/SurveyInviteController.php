<?php

namespace AssetIQ\Http\Controllers\Surveys;

use AssetIQ\Http\Controllers\Controller;
use AssetIQ\Http\Requests\SurveyRequest;
use AssetIQ\Mail\SurveyInvite;
use AssetIQ\Models\Audits\Audit;
use AssetIQ\Models\Audits\AuditTarget;
use AssetIQ\Models\SecurityRoleOptions;
use AssetIQ\Models\Surveys\Survey;
use AssetIQ\Models\Surveys\SurveyAnswer;
use AssetIQ\Models\Surveys\SurveyTarget;
use AssetIQ\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SurveyInviteController extends Controller
{
	public function __construct()
	{
		$this->middleware( 'role:' . implode( ',', [ SecurityRoleOptions::SurveyAdmin, SecurityRoleOptions::OperationalAdmin, SecurityRoleOptions::OperationalViewer ] ) )->only('invite');
		$this->middleware( 'role:' . implode( ',', [ SecurityRoleOptions::SurveyAdmin ] ) )->only('post_invite', 'send_invites', 'delete_invite');
	}

	public function invite( Request $request, Survey $survey )
	{
		$targets = SurveyTarget::with( 'user' )
			->where( 'survey_id', '=', $survey->id )
			->orderBy( 'last_name' )
			->orderBy( 'first_name' )
			->get();

		$audit = Audit::where( 'survey_id', '=', $survey->id )->first();

		if ( !is_null( $audit ) )
		{
			return redirect()->route( 'audits.invite', [ $audit->id ] );
		}

		return view( 'survey.invite', [
			'survey'  => $survey,
			'audit'   => $audit,
			'targets' => $targets
		] );
	}

	public function post_invite( SurveyRequest $request, Survey $survey )
	{
		$user = User::find( $request->user_id );
		if ( is_null( $user ) )
		{
			alert()->error( 'User not found.' );

			return back();
		}

		$target = SurveyTarget::create_target( $survey, $user );

		alert()->success( 'Person has been added to the invite list.' );

		return back();
	}

	public function send_invites( Request $request, Survey $survey )
	{
		if ( !$request->has( 'targets' ) || !is_array( $request->targets ) || count( $request->targets ) == 0 )
		{
			alert()->error( 'No users selected.' );

			return back();
		}

		$targets = SurveyTarget::with( 'user', 'survey', 'survey.author' )->whereIn( 'id', $request->targets )->get();

		foreach ( $targets as $target )
		{
			Mail::to( $target->email )->send( new SurveyInvite( $target ) );
		}

		alert()->success( 'Invites have been scheduled to send.' );

		return back();
	}

	public function delete_invite( SurveyRequest $request, Survey $survey, SurveyTarget $target )
	{
		$target->delete();

		alert()->success( 'Person has been removed from the survey.' );

		return back();
	}
	public function print( Request $request, Survey $survey, SurveyTarget $target )
	{
		// Reload survey with relations
		//
		$survey = Survey::with( [ 'sections' => function ( $query ) {
			$query->orderBy( 'order' );
		}, 'sections.copies'                 => function ( $query ) use ( $target ) {
			$query->where( 'survey_target_id', '=', $target->id )->orderBy( 'copy_number' );
		}, 'sections.questions'              => function ( $query ) {
			$query->orderBy( 'order' );
		} ] )->where( 'id', '=', $survey->id )->first();


		$is_complete         = true;
		$is_clonable_used    = false;
		$is_description_used = false;
		$is_details_used     = false;
		$is_url_used         = false;
		foreach ( $survey->sections as $section )
		{
			$is_clonable_used    = $is_clonable_used || $section->is_cloneable;
			$is_description_used = $is_description_used || ( !is_null( $section->description ) && strlen( trim( $section->description ) ) > 0 );

			$section->copies = $section->copies()->where( 'survey_target_id', '=', $target->id )->get();
			foreach ( $section->copies as $copy )
			{
				$questions = $section->questions()->orderBy( 'order' )->get();
				foreach ( $questions as $question )
				{
					$is_details_used = $is_description_used || ( !is_null( $question->details ) && strlen( trim( $question->details ) ) > 0 );
					$is_url_used     = $is_url_used || ( !is_null( $question->additional_info_url ) && strlen( trim( $question->additional_info_url ) ) > 0 );

					$answer = SurveyAnswer::where( 'survey_target_id', '=', $target->id )
						->where( 'survey_question_id', '=', $question->id )
						->where( 'survey_section_copy_id', '=', $copy->id )
						->first();
					if ( is_null( $answer ) )
					{
						$is_complete = false;
					}

					$question->answer = is_null( $answer ) ? null : $answer;

					$question->children = $question->children()->orderBy( 'order' )->get();
					foreach ( $question->children as $child_question )
					{
						$is_details_used = $is_description_used || ( !is_null( $child_question->details ) && strlen( trim( $child_question->details ) ) > 0 );
						$is_url_used     = $is_url_used || ( !is_null( $child_question->additional_info_url ) && strlen( trim( $child_question->additional_info_url ) ) > 0 );

						$child_answer = SurveyAnswer::where( 'survey_target_id', '=', $target->id )
							->where( 'survey_question_id', '=', $child_question->id )
							->where( 'survey_section_copy_id', '=', $copy->id )
							->first();

						$child_question->answer = is_null( $child_answer ) ? null : $child_answer;
					}
				}

				$copy->questions = $questions;
			}
		}

		$survey->is_complete = $is_complete;

		if ( !$is_complete )
		{
			$target->last_viewed_at = Carbon::now();
			$target->save();
		}

		$audit_target = AuditTarget::with( 'location', 'audit', 'audit.created_by', 'audit.template' )->where( 'survey_target_id', '=', $target->id )->first();

		return view( 'survey.print', [
			'survey'              => $survey,
			'target'              => $target,
			'audit_target'        => $audit_target,
			'is_clonable_used'    => $is_clonable_used,
			'is_description_used' => $is_description_used,
			'is_details_used'     => $is_details_used,
			'is_url_used'         => $is_url_used,
		] );
	}
}
