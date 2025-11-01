<?php

namespace App\Http\Controllers\Surveys;

use App\Http\Controllers\Controller;
use App\Models\Audits\AuditTarget;
use App\Models\SecurityRoleOptions;
use App\Models\Surveys\Survey;
use App\Models\Surveys\SurveyAnswer;
use App\Models\Surveys\SurveyQuestion;
use App\Models\Surveys\SurveyQuestionTypeOptions;
use App\Models\Surveys\SurveySection;
use App\Models\Surveys\SurveySectionCopy;
use App\Models\Surveys\SurveyTarget;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SurveyTakeController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'role:' . implode( ',', [ SecurityRoleOptions::SurveyAdmin ] ), [
            'except' => [
                'submit_file',
                'submit_answer',
                'submit_answers',
                'take',
                'clone_section',
                'remove_cloned_section',
                'print'
            ]
        ] );
    }

    public function take( Request $request, $survey_hash, $target_hash )
    {
        $target = SurveyTarget::where( 'hash', '=', $target_hash )->first();
        if ( is_null( $target ) )
        {
            abort( 404 );
        }

        $survey = Survey::with( [
                                    'sections'           => function ( $query ) {
                                        $query->orderBy( 'order' );
                                    },
                                    'sections.copies'    => function ( $query ) use ( $target ) {
                                        $query->where( 'survey_target_id', '=', $target->id )->orderBy( 'copy_number' );
                                    },
                                    'sections.questions' => function ( $query ) {
                                        $query->orderBy( 'order' );
                                    }
                                ] )->where( 'hash', '=', $survey_hash )->first();
        if ( is_null( $survey ) )
        {
            abort( 404 );
        }


        $is_complete = true;
        foreach ( $survey->sections as $section )
        {
            $section->copies = $section->copies()->where( 'survey_target_id', '=', $target->id )->get();
            foreach ( $section->copies as $copy )
            {
                $questions = $section->questions()->orderBy( 'order' )->get();
                foreach ( $questions as $question )
                {
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

        if ( ! $is_complete )
        {
            $target->last_viewed_at = Carbon::now();
            $target->save();
        }

        $audit_target = AuditTarget::with( 'location', 'audit', 'audit.created_by', 'audit.template' )->where( 'survey_target_id', '=', $target->id )->first();

        return view( 'survey.take', [
            'survey'       => $survey,
            'target'       => $target,
            'audit_target' => $audit_target
        ] );
    }

    public function print( Request $request, $survey_hash, $target_hash )
    {
        $target = SurveyTarget::where( 'hash', '=', $target_hash )->first();
        if ( is_null( $target ) )
        {
            abort( 404 );
        }

        $survey = Survey::with( [
                                    'sections'           => function ( $query ) {
                                        $query->orderBy( 'order' );
                                    },
                                    'sections.copies'    => function ( $query ) use ( $target ) {
                                        $query->where( 'survey_target_id', '=', $target->id )->orderBy( 'copy_number' );
                                    },
                                    'sections.questions' => function ( $query ) {
                                        $query->orderBy( 'order' );
                                    }
                                ] )->where( 'hash', '=', $survey_hash )->first();
        if ( is_null( $survey ) )
        {
            abort( 404 );
        }

        $is_complete         = true;
        $is_clonable_used    = false;
        $is_description_used = false;
        $is_details_used     = false;
        $is_url_used         = false;
        foreach ( $survey->sections as $section )
        {
            $is_clonable_used    = $is_clonable_used || $section->is_cloneable;
            $is_description_used = $is_description_used || ( ! is_null( $section->description ) && strlen( trim( $section->description ) ) > 0 );

            $section->copies = $section->copies()->where( 'survey_target_id', '=', $target->id )->get();
            foreach ( $section->copies as $copy )
            {
                $questions = $section->questions()->orderBy( 'order' )->get();
                foreach ( $questions as $question )
                {
                    $is_details_used = $is_description_used || ( ! is_null( $question->details ) && strlen( trim( $question->details ) ) > 0 );
                    $is_url_used     = $is_url_used || ( ! is_null( $question->additional_info_url ) && strlen( trim( $question->additional_info_url ) ) > 0 );

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
                        $is_details_used = $is_description_used || ( ! is_null( $child_question->details ) && strlen( trim( $child_question->details ) ) > 0 );
                        $is_url_used     = $is_url_used || ( ! is_null( $child_question->additional_info_url ) && strlen( trim( $child_question->additional_info_url ) ) > 0 );

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

        if ( ! $is_complete )
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

    public function submit_answer( Request $request, $survey_hash, $target_hash )
    {
        if ( ! $request->ajax() )
        {
            abort( 404, 'Ajax only' );
        }

        $survey = Survey::with( 'sections', 'sections.questions' )->where( 'hash', '=', $survey_hash )->first();
        if ( is_null( $survey ) )
        {
            abort( 404, 'Survey not found' );
        }

        $target = $survey->targets()->where( 'hash', '=', $target_hash )->first();
        if ( is_null( $target ) )
        {
            abort( 404, 'Target not found' );
        }

        if ( ! $request->has( 'question_id' ) )
        {
            abort( 404, 'Missing parameter' );
        }

        $question = SurveyQuestion::find( $request->question_id );
        if ( is_null( $question ) )
        {
            abort( 404, 'Question not found' );
        }
        if ( $question->survey_section->survey_id != $survey->id )
        {
            abort( 404, 'Survey does not match' );
        }

        $section_copy = SurveySectionCopy::find( $request->copy_id );
        if ( is_null( $section_copy ) )
        {
            abort( 404, 'Copy not found' );
        }
        if ( $section_copy->survey_section_id != $question->survey_section_id )
        {
            abort( 404, 'Section does not match' );
        }
        if ( $section_copy->survey_target_id != $target->id )
        {
            abort( 404, 'Target does not match' );
        }

        // Validate response type
        //
        $rule = $question->validation_rule( $section_copy );
        if ( ! is_null( $rule ) )
        {
            $rules = [
                'current_answer' => $rule
            ];
            if ( ! $this->validate( $request, $rules ) )
            {
                abort( 404 );
            }
        }

        $answer = SurveyAnswer::where( 'survey_target_id', '=', $target->id )
                              ->where( 'survey_question_id', '=', $question->id )
                              ->where( 'survey_section_copy_id', '=', $section_copy->id )
                              ->first();
        if ( is_null( $answer ) )
        {
            $answer                         = new SurveyAnswer();
            $answer->survey_target_id       = $target->id;
            $answer->survey_question_id     = $question->id;
            $answer->survey_section_copy_id = $section_copy->id;
        }

        $current_answer = is_array( $request->current_answer ) ? $request->current_answer : [ $request->current_answer ];
        if ( $survey->type == SurveyQuestionTypeOptions::Checkboxes )
        {
            $pruned_answers = [];
            foreach ( $current_answer as $answer )
            {
                if ( in_array( $answer, $question->allowed_values ) )
                {
                    $pruned_answers[] = $answer;
                }
            }
            $current_answer = $pruned_answers;
        }

        $answer->answered_at = Carbon::now();
        $answer->answer      = $current_answer;

        $answer->save();

        $is_completed = $target->is_survey_completed();
        if ( $is_completed != $target->is_completed )
        {
            $target->is_completed = $is_completed;
            $target->save();
        }

        $response           = new \stdClass();
        $response->data     = $request->all();
        $response->is_error = false;

        return response()->json( $response );
    }

    public function submit_file( Request $request, $survey_hash, $target_hash )
    {
        if ( ! $request->ajax() )
        {
            abort( 404 );
        }

        $survey = Survey::with( 'sections', 'sections.questions' )->where( 'hash', '=', $survey_hash )->first();
        if ( is_null( $survey ) )
        {
            abort( 404 );
        }

        $target = $survey->targets()->where( 'hash', '=', $target_hash )->first();
        if ( is_null( $target ) )
        {
            abort( 404 );
        }

        if ( ! $request->has( 'question_id' ) )
        {
            abort( 404 );
        }

        $question = SurveyQuestion::find( $request->question_id );
        if ( is_null( $question ) )
        {
            abort( 404 );
        }
        if ( $question->survey_section->survey_id != $survey->id )
        {
            abort( 404 );
        }

        $section_copy = SurveySectionCopy::find( $request->copy_id );
        if ( is_null( $section_copy ) )
        {
            abort( 404, 'Copy not found' );
        }
        if ( $section_copy->survey_section_id != $question->survey_section_id )
        {
            abort( 404, 'Section does not match' );
        }
        if ( $section_copy->survey_target_id != $target->id )
        {
            abort( 404, 'Target does not match' );
        }

        // Validate response type
        //
        $rule = $question->validation_rule( $section_copy );
        if ( ! is_null( $rule ) )
        {
            $rules = [
                'file' => $rule
            ];
            if ( ! $this->validate( $request, $rules ) )
            {
                abort( 404 );
            }
        }

        $answer = SurveyAnswer::from_uploaded_file_answer( $request, $question, $target, $section_copy );
        $file   = $answer->file();

        $is_completed = $target->is_survey_completed();
        if ( $is_completed != $target->is_completed )
        {
            $target->is_completed = $is_completed;
            $target->save();
        }

        $response                      = new \stdClass();
        $response->data                = $request->all();
        $response->is_error            = false;
        $response->download_url        = is_null( $file ) ? null : $file->download_url;
        $response->download_inline_url = is_null( $file ) ? null : $file->download_inline_url;
        $response->file_name           = is_null( $file ) ? null : $file->name;
        $response->is_image            = is_null( $file ) ? false : $file->is_image();

        return response()->json( $response );
    }

    public function submit_answers( Request $request, $survey_hash, $target_hash )
    {
        $survey = Survey::with( 'sections', 'sections.questions' )->where( 'hash', '=', $survey_hash )->first();
        if ( is_null( $survey ) )
        {
            abort( 404 );
        }

        $target = $survey->targets()->where( 'hash', '=', $target_hash )->first();
        if ( is_null( $target ) )
        {
            abort( 404 );
        }

        $request_data = [];

        // Validation
        //
        $rules          = [];
        $ignored_fields = [ '_token' ];
        foreach ( $request->all() as $field => $value )
        {
            if ( ! in_array( $field, $ignored_fields ) )
            {
                $parts = explode( '_', $field );
                if ( count( $parts ) != 2 )
                {
                    continue;
                }
                $c_parts = explode( '-', $parts[ 0 ] );
                if ( count( $c_parts ) != 2 && $c_parts[ 0 ] != 'c' )
                {
                    continue;
                }
                $cid  = $c_parts[ 1 ];
                $copy = SurveySectionCopy::find( $cid );
                if ( is_null( $copy ) )
                {
                    continue;
                }
                if ( $copy->survey_target_id != $target->id )
                {
                    continue;
                }

                $q_parts = explode( '-', $parts[ 1 ] );
                if ( count( $q_parts ) != 2 && $q_parts[ 0 ] != 'q' )
                {
                    continue;
                }
                $qid      = $q_parts[ 1 ];
                $question = SurveyQuestion::find( $qid );
                if ( is_null( $question ) )
                {
                    continue;
                }
                if ( $question->survey_section->survey_id != $survey->id )
                {
                    continue;
                }

                if ( ! array_key_exists( $cid, $request_data ) )
                {
                    $obj            = new \stdClass();
                    $obj->copy      = $copy;
                    $obj->questions = [];
                    $obj->answers   = [];

                    $request_data[ $cid ] = $obj;
                }

                $request_data[ $cid ]->questions[ $qid ] = $question;
                $request_data[ $cid ]->answers[ $qid ]   = $value;

                $rule = $question->validation_rule( $copy );
                if ( ! is_null( $rule ) )
                {
                    $rules[ $field ] = $rule;
                }
            }
        }

        if ( count( $rules ) )
        {
            if ( ! $this->validate( $request, $rules ) )
            {
                abort( 404 );
            }
        }

        // Save any changed data (there should be none if the ajax is working)
        //
        foreach ( $request_data as $cid => $obj )
        {
            foreach ( $obj->questions as $qid => $question )
            {
                $current_answer = $obj->answers[ $qid ];
                $current_answer = is_array( $current_answer ) ? $current_answer : [ $current_answer ];
                if ( $survey->type == SurveyQuestionTypeOptions::Checkboxes )
                {
                    $pruned_answers = [];
                    foreach ( $current_answer as $answer )
                    {
                        if ( in_array( $answer, $question->allowed_values ) )
                        {
                            $pruned_answers[] = $answer;
                        }
                    }
                    $current_answer = $pruned_answers;
                }

                $answer = SurveyAnswer::where( 'survey_target_id', '=', $target->id )
                                      ->where( 'survey_question_id', '=', $question->id )
                                      ->where( 'survey_section_copy_id', '=', $obj->copy->id )
                                      ->first();
                if ( is_null( $answer ) )
                {
                    $answer                         = new SurveyAnswer();
                    $answer->survey_target_id       = $target->id;
                    $answer->survey_question_id     = $question->id;
                    $answer->survey_section_copy_id = $obj->copy->id;
                }

                if ( $answer->answer != $current_answer )
                {
                    $answer->answered_at = Carbon::now();
                    $answer->answer      = $current_answer;

                    $answer->save();
                }
            }
        }

        foreach ( $survey->sections as $section )
        {
            $questions = $section->questions()->orderBy( 'order' )->get();

            $section->copies = $section->copies()->where( 'survey_target_id', '=', $target->id )->get();
            foreach ( $section->copies as $copy )
            {
                $question_ids_with_no_answers_in_section = [];

                $data = array_key_exists( $copy->id, $request_data ) ? $request_data[ $copy->id ] : null;

                foreach ( $questions as $question )
                {
                    if ( is_null( $data ) || ! array_key_exists( $question->id, $data->questions ) )
                    {
                        $question_ids_with_no_answers_in_section[] = $question->id;
                    }
                    foreach ( $question->children as $child_question )
                    {
                        if ( is_null( $data ) || ! array_key_exists( $question->id, $data->questions ) )
                        {
                            $question_ids_with_no_answers_in_section[] = $child_question->id;
                        }
                    }
                }

                if ( count( $question_ids_with_no_answers_in_section ) > 0 )
                {
                    SurveyAnswer::where( 'survey_target_id', '=', $target->id )
                                ->whereIn( 'survey_question_id', $question_ids_with_no_answers_in_section )
                                ->where( 'survey_section_copy_id', '=', $copy->id )
                                ->delete();
                }
            }
        }

        $is_completed = $target->is_survey_completed();
        if ( $is_completed != $target->is_completed )
        {
            $target->is_completed = $is_completed;
            $target->save();
        }

        alert()->success( 'Thanks for you submission.' );

        return back();
    }

    public function clone_section( Request $request, $survey_hash, $target_hash, $section_id )
    {
        $survey = Survey::where( 'hash', '=', $survey_hash )->first();
        if ( is_null( $survey ) )
        {
            abort( 404 );
        }

        $target = $survey->targets()->where( 'hash', '=', $target_hash )->first();
        if ( is_null( $target ) )
        {
            abort( 404 );
        }

        $section = $survey->sections()->where( 'id', '=', $section_id )->first();
        if ( is_null( $section ) )
        {
            abort( 404 );
        }
        if ( ! $section->is_cloneable )
        {
            abort( 404 );
        }

        $current_copy_count = SurveySectionCopy::where( 'survey_section_id', '=', $section->id )
                                               ->where( 'survey_target_id', '=', $target->id )
                                               ->count();

        $copy                    = new SurveySectionCopy();
        $copy->survey_section_id = $section->id;
        $copy->survey_target_id  = $target->id;
        $copy->copy_number       = $current_copy_count;

        $copy->save();

        alert()->success( 'Section has been cloned.' );

        return back();
    }

    public function remove_cloned_section( Request $request, $survey_hash, $target_hash, $copy_id )
    {
        $survey = Survey::with( 'sections', 'sections.questions' )->where( 'hash', '=', $survey_hash )->first();
        if ( is_null( $survey ) )
        {
            abort( 404 );
        }

        $target = $survey->targets()->where( 'hash', '=', $target_hash )->first();
        if ( is_null( $target ) )
        {
            abort( 404 );
        }

        $copy = $target->section_copies()->where( 'id', '=', $copy_id )->first();
        if ( is_null( $copy ) )
        {
            abort( 404 );
        }
        if ( $copy->copy_number == 0 ) // Can't delete the original
        {
            abort( 404 );
        }

        // Copies that came after me
        //
        $copies = SurveySectionCopy::where( 'survey_section_id', '=', $copy->survey_section_id )
                                   ->where( 'survey_target_id', '=', $target->id )
                                   ->where( 'copy_number', '>', $copy->copy_number )
                                   ->get();

        // Delete the copy
        //
        $copy->delete();

        // Renumber the latter copies
        //
        foreach ( $copies as $c )
        {
            $c->clone_number = $c->clone_number - 1;
            $c->save();
        }

        alert()->success( 'Section has been deleted.' );

        return back();
    }

    private function clone_question( SurveySection $section, SurveyQuestion $question, $parent_id )
    {
        $q_clone                      = new SurveyQuestion();
        $q_clone->survey_section_id   = $section->id;
        $q_clone->parent_id           = $parent_id;
        $q_clone->order               = $question->order;
        $q_clone->parent_answers      = $question->parent_answers;
        $q_clone->question            = $question->question;
        $q_clone->details             = $question->details;
        $q_clone->additional_info_url = $question->additional_info_url;
        $q_clone->type                = $question->type;
        $q_clone->allowed_values      = $question->allowed_values;
        $q_clone->is_answer_required  = $question->is_answer_required;

        $q_clone->save();

        return $q_clone;
    }
}
