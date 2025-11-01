<?php

namespace App\Http\Controllers\Surveys;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyRequest;
use App\Models\Surveys\Survey;
use App\Models\Surveys\SurveyAnswer;
use App\Models\Surveys\SurveyQuestion;
use App\Models\Surveys\SurveySectionCopy;
use App\Models\Surveys\SurveyStatusOptions;
use App\Models\Surveys\SurveyTarget;
use App\Models\Task;
use App\ProjectX\Utilities\AuditAndSurvey\AnswerHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SurveyController extends Controller
{
    public function __construct()
    {
    }

    public function index( Request $request )
    {
        $surveys = Survey::with(
            'sections', 'author', 'targets' )->get();

        $survey_ids = [];
        foreach ( $surveys as $survey )
        {
            $survey_ids[] = $survey->id;

            $question_count = 0;
            foreach ( $survey->sections as $section )
            {
                $question_count += SurveyQuestion::where( 'survey_section_id', '=', $section->id )->count();
            }
            $survey->question_count = $question_count;

            $invited_count   = 0;
            $completed_count = 0;
            foreach ( $survey->targets as $target )
            {
                $invited_count += 1;

                if ( $target->is_completed )
                {
                    $completed_count += 1;
                }
            }
            $survey->invited_count   = $invited_count;
            $survey->completed_count = $completed_count;
        }

        $audits    = Audit::whereIn( 'survey_id', $survey_ids )->get();
        $audit_lut = [];
        foreach ( $audits as $audit )
        {
            $audit_lut[ $audit->survey_id ] = $audit;
        }

        return view( 'survey.index', [
            'surveys'   => $surveys,
            'audit_lut' => $audit_lut
        ] );
    }

    public function show( SurveyRequest $request, Survey $survey )
    {
        $survey->answer_summary_aggregates();

        $audit = Audit::where( 'survey_id', '=', $survey->id )->first();

        return view( 'survey.dashboard', [
            'survey' => $survey,
            'audit'  => $audit
        ] );
    }

    public function download_answers( SurveyRequest $request, Survey $survey )
    {
        $survey->answer_summary_aggregates();

        $rows = [];

        $header = [
            '#section',
            'section_description',
            'question',
            'response',
            'count',
            'percent',
            'parent_answers',
            'followup_question',
            'followup_response',
            'followup_count',
            'followup_percent'
        ];

        $rows[] = $header;

        foreach ( $survey->sections as $section )
        {
            if ( $section->clone_number == 0 )
            {
                foreach ( $section->questions as $question )
                {
                    foreach ( $question->answer_summary as $response => $obj )
                    {
                        $cols   = [];
                        $cols[] = $section->title;
                        $cols[] = $section->description;
                        $cols[] = $question->question;
                        $cols[] = $response;
                        $cols[] = $obj->count;
                        $cols[] = number_format( $obj->percent, 1 );

                        $rows[] = $cols;
                    }

                    foreach ( $question->children as $child_question )
                    {
                        foreach ( $child_question->answer_summary as $response => $obj )
                        {
                            $cols   = [];
                            $cols[] = '--';
                            $cols[] = '--';
                            $cols[] = '--';
                            $cols[] = '--';
                            $cols[] = '--';
                            $cols[] = '--';
                            $cols[] = implode( ', ', $child_question->parent_answers );
                            $cols[] = $child_question->question;
                            $cols[] = $response;
                            $cols[] = $obj->count;
                            $cols[] = number_format( $obj->percent, 1 );

                            $rows[] = $cols;
                        }
                    }
                }
            }
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray( $rows );

        $writer = IOFactory::createWriter( $spreadsheet, 'Xls' );

        $name = Str::slug( $survey->title, '_' );

        header( 'Content-Type: application/vnd.ms-excel' );
        header( 'Content-Disposition: attachment; filename="' . $name . '.xls"' );
        $writer->save( "php://output" );
    }

    public function details( SurveyRequest $request, Survey $survey )
    {
        $selected_location_code = $request->l;

        $survey->answer_summary_aggregates();

        $survey_target_ids = [];
        foreach ( $survey->sections as $section )
        {
            foreach ( $section->questions as $question )
            {
                foreach ( $question->answers as $answer )
                {
                    if ( ! in_array( $answer->survey_target_id, $survey_target_ids ) )
                    {
                        $survey_target_ids[] = $answer->survey_target_id;
                    }
                }

                foreach ( $question->children as $child_question )
                {
                    foreach ( $child_question->answers as $answer )
                    {
                        if ( ! in_array( $answer->survey_target_id, $survey_target_ids ) )
                        {
                            $survey_target_ids[] = $answer->survey_target_id;
                        }
                    }
                }
            }
        }

        $survey_targets = SurveyTarget::whereIn( 'id', $survey_target_ids )
                                      ->orderBy( 'last_name' )
                                      ->orderBy( 'first_name' )
                                      ->get();

        $audit = Audit::where( 'survey_id', '=', $survey->id )->first();

        $audit_targets    = AuditTarget::with( 'location' )->whereIn( 'survey_target_id', $survey_target_ids )->get();
        $audit_target_lut = [];
        $location_lut     = [];
        foreach ( $audit_targets as $audit_target )
        {
            $audit_target_lut[ $audit_target->survey_target_id ] = $audit_target;
            $location_lut[ $audit_target->location->code ]       = $audit_target->location;
        }

        ksort( $location_lut );

        return view( 'survey.details', [
            'survey'                 => $survey,
            'audit'                  => $audit,
            'audit_target_lut'       => $audit_target_lut,
            'location_lut'           => $location_lut,
            'selected_location_code' => $selected_location_code,
            'survey_targets'         => $survey_targets
        ] );
    }

    public function answers_table( SurveyRequest $request, Survey $survey )
    {
        $survey->load( [
                           'sections'                    => function ( $q ) {
                               $q->orderBy( 'order' );
                           },
                           'sections.questions'          => function ( $q ) {
                               $q->orderBy( 'order' );
                           },
                           'sections.questions.children' => function ( $q ) {
                               $q->orderBy( 'order' );
                           },
                           'sections.copies'             => function ( $q ) {
                               $q->orderBy( 'copy_number' );
                           }
                       ] );

        $audit = Audit::where( 'survey_id', '=', $survey->id )->first();

        $header = [];
        if ( ! is_null( $audit ) )
        {
            $header[] = 'Location';
            $header[] = 'Division';
            $header[] = 'Regional Leader';
        }
        $header[] = 'Person';
        $header[] = 'Last Viewed';

        // Find the max number of copies per section for all targets
        //
        $sql                    = <<<SQL
select a.survey_section_id, max(a.count) as max_count
from (
	select ssc.survey_section_id, ssc.survey_target_id, count(ssc.survey_section_id) as count
	from survey_section_copies as ssc
	where ssc.survey_target_id in (
		select id from survey_targets where survey_id = {$survey->id}
	)
	group by ssc.survey_section_id, ssc.survey_target_id
) as a
group by a.survey_section_id;
SQL;
        $rows                   = DB::select( $sql );
        $max_copies_per_section = [];
        foreach ( $rows as $row )
        {
            $max_copies_per_section[ $row->survey_section_id ] = $row->max_count;
        }

        $targets = SurveyTarget::has( 'answers' )->where( 'survey_id', '=', $survey->id )->get();

        foreach ( $survey->sections as $section )
        {
            $num_copies = $max_copies_per_section[ $section->id ];
            for ( $copy = 0; $copy < $num_copies; $copy ++ )
            {
                foreach ( $section->questions as $question_index => $question )
                {
                    $question_number = $question->order + 1;
                    $section_title   = $section->title;
                    if ( $copy > 0 )
                    {
                        $section_title .= ' [copy #' . $copy . ']';
                    }
                    $title    = $section_title . '<br />' . $question_number . '. ' . $question->question;
                    $header[] = $title;

                    foreach ( $question->children as $child_index => $child )
                    {
                        $question_ids[]             = $child->id;
                        $question_lut[ $child->id ] = $child;

                        $title    = $section_title . '<br />' . $question_number . '-' . chr( ord( 'A' ) + $child_index ) . '. ' . $child->question;
                        $header[] = $title;
                    }
                }
            }
        }

        $target_ids       = $targets->pluck( 'id' )->toArray();
        $audit_targets    = AuditTarget::with( 'location' )->whereIn( 'survey_target_id', $target_ids )->get();
        $audit_target_lut = [];
        $location_lut     = [];
        foreach ( $audit_targets as $audit_target )
        {
            $audit_target_lut[ $audit_target->survey_target_id ] = $audit_target;
            $location_lut[ $audit_target->location->code ]       = $audit_target->location;
        }

        ksort( $location_lut );

        $answer_helper = new AnswerHelper();

        $survey_section_copy_ids = [];
        $section_copies_lut      = [];
        foreach ( $survey->sections as $section )
        {
            $section_copies_lut[ $section->id ] = [];
            foreach ( $section->copies as $copy )
            {
                if ( ! array_key_exists( $copy->survey_target_id, $section_copies_lut[ $section->id ] ) )
                {
                    $section_copies_lut[ $section->id ][ $copy->survey_target_id ] = [];
                }
                $section_copies_lut[ $section->id ][ $copy->survey_target_id ][] = $copy;

                $survey_section_copy_ids[] = $copy->id;
            }
        }

        $all_answers = SurveyAnswer::whereIn( 'survey_target_id', $target_ids )->get();
        $answer_lut  = [];
        foreach ( $all_answers as $answer )
        {
            $target_id = $answer->survey_target_id;
            if ( ! array_key_exists( $target_id, $answer_lut ) )
            {
                $answer_lut[ $target_id ] = [];
            }
            $question_id = $answer->survey_question_id;
            if ( ! array_key_exists( $question_id, $answer_lut[ $target_id ] ) )
            {
                $answer_lut[ $target_id ][ $question_id ] = [];
            }
            $copy_id                                              = $answer->survey_section_copy_id;
            $answer_lut[ $target_id ][ $question_id ][ $copy_id ] = $answer;
        }

        $rows = [];
        foreach ( $targets as $target )
        {
            $cols         = [];
            $audit_target = array_key_exists( $target->id, $audit_target_lut ) ? $audit_target_lut[ $target->id ] : null;
            if ( is_null( $audit_target ) )
            {
                continue;
            }

            if ( ! is_null( $audit ) )
            {
                $cols[]          = $this->create_column( $audit_target->location->code );
                $division        = is_null( $audit_target->location->sub_division_id ) ? '--' : $audit_target->location->sub_division->division->name;
                $cols[]          = $this->create_column( $division );
                $regional_leader = is_null( $audit_target->location->regional_leader_id ) ? '--' : $audit_target->location->regional_leader->last_name . ', ' . $audit_target->location->regional_leader->first_name;
                $cols[]          = $this->create_column( $regional_leader );
            }
            $cols[] = $this->create_column( $target->last_name . ', ' . $target->first_name );
            $cols[] = $this->create_column( $target->last_viewed_at->toDateTimeString() );

            foreach ( $survey->sections as $section )
            {
                $section_copies = array_key_exists( $target->id, $section_copies_lut[ $section->id ] ) ?
                    $section_copies_lut[ $section->id ][ $target->id ] : [];
                foreach ( $section_copies as $copy )
                {
                    foreach ( $section->questions as $question_index => $question )
                    {
                        $answer = array_key_exists( $target->id, $answer_lut ) && array_key_exists( $question->id, $answer_lut[ $target->id ] ) && array_key_exists( $copy->id, $answer_lut[ $target->id ][ $question->id ] ) ?
                            $answer_lut[ $target->id ][ $question->id ][ $copy->id ] : null;

                        $answer_str = $answer_helper->to_string( $question, $answer );
                        $cols[]     = $this->create_column( $answer_str, $answer );

                        foreach ( $question->children as $child_index => $child )
                        {
                            $answer = array_key_exists( $target->id, $answer_lut ) && array_key_exists( $child->id, $answer_lut[ $target->id ] ) && array_key_exists( $copy->id, $answer_lut[ $target->id ][ $child->id ] ) ?
                                $answer_lut[ $target->id ][ $child->id ][ $copy->id ] : null;

                            $answer_str = $answer_helper->to_string( $child, $answer );
                            $cols[]     = $this->create_column( $answer_str, $answer );
                        }
                    }
                }
                $num_copies = count( $section_copies );
                for ( $c = $num_copies; $c < $max_copies_per_section[ $section->id ]; $c ++ )
                {
                    foreach ( $survey->questions as $question_index => $question )
                    {
                        $cols[] = $this->create_column();

                        foreach ( $question->children as $child_index => $child )
                        {
                            $cols[] = $this->create_column();
                        }
                    }
                }
            }

            $rows[] = $cols;
        }

        return view( 'survey.details_table', [
            'survey'       => $survey,
            'audit'        => $audit,
            'header'       => $header,
            'rows'         => $rows,
            'location_lut' => $location_lut
        ] );
    }

    public function destroy( SurveyRequest $request, Survey $survey )
    {
        $survey->delete();

        alert()->success( 'Survey has been deleted.' );

        return back();
    }

    public function stop( Request $request, Survey $survey )
    {
        $survey->status    = SurveyStatusOptions::Stopped;
        $survey->status_at = Carbon::now();

        $survey->save();

        alert()->success( 'The survey has been stopped.' );

        return back();
    }

    public function start( Request $request, Survey $survey )
    {
        $survey->status    = SurveyStatusOptions::Started;
        $survey->status_at = Carbon::now();

        $survey->save();

        alert()->success( 'The survey has been resumed.' );

        return back();
    }

    private function create_column( $html = null, SurveyAnswer $answer = null )
    {
        $col                = new \stdClass();
        $col->html          = is_null( $html ) ? '--' : $html;
        $col->survey_answer = $answer;
        $col->tasks         = is_null( $answer ) ? [] : Task::where( 'owner_type', '=', SurveyAnswer::class )->where( 'owner_id', '=', $answer->id )->get();

        return $col;
    }
}
