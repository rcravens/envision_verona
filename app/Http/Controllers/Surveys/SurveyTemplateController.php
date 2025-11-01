<?php

namespace App\Http\Controllers\Surveys;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyTemplateRequest;
use App\Models\Surveys\Survey;
use App\Models\Surveys\SurveyBlueprint;
use App\Models\Surveys\SurveyBlueprintQuestion;
use App\Models\Surveys\SurveyBlueprintSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyTemplateController extends Controller
{
    public function __construct()
    {
    }

    public function index( Request $request )
    {
        $templates = SurveyBlueprint::with( 'author', 'sections', 'sections.questions' )->get();

        foreach ( $templates as $template )
        {
            $total_questions = 0;
            foreach ( $template->sections as $section )
            {
                foreach ( $section->questions as $question )
                {
                    $total_questions += 1;
                    foreach ( $question->children as $child_question )
                    {
                        $total_questions += 1;
                    }
                }
            }

            $template->total_questions = $total_questions;

            $template->total_surveys = Survey::where( 'survey_template_id', '=', $template->id )->count();
        }

        return view( 'survey.template.index', [
            'templates' => $templates
        ] );
    }

    public function store( SurveyTemplateRequest $request )
    {
        $template              = new SurveyBlueprint();
        $template->author_id   = Auth::user()->id;
        $template->title       = $request->title;
        $template->description = $request->description;
        $template->department  = $request->department;

        $template->save();

        alert()->success( 'Template created.' );

        return redirect()->route( 'survey_templates.edit', [ $template->id ] );
    }

    public function edit( SurveyTemplateRequest $request, SurveyBlueprint $survey_template )
    {
        return view( 'survey.template.edit', [
            'template' => $survey_template
        ] );
    }

    public function destroy( SurveyTemplateRequest $request, SurveyBlueprint $survey_template )
    {
        $survey_template->delete();

        alert()->success( 'Template has been deleted.' );

        return back();
    }


    public function preview( Request $request, SurveyBlueprint $template )
    {
        $template->load( [
                             'sections' => function ( $query ) {
                                 $query->orderBy( 'Order' );
                             }
                         ] );

        $template->is_complete = false;

        // Mock up some section copies so we can use the production take view
        //
        foreach ( $template->sections as $section )
        {
            $copies            = [];
            $copy              = new \stdClass();
            $copy->id          = 999;
            $copy->copy_number = 0;
            $copy->questions   = $section->questions;

            $copies[] = $copy;

            $section->copies = $copies;
        }

        return view( 'survey.take', [
            'survey' => $template,
            'target' => null
        ] );
    }

    public function clone( Request $request, SurveyBlueprint $template )
    {
        $clone              = new SurveyTemplate();
        $clone->author_id   = Auth::user()->id;
        $clone->title       = $template->title . ' (clone)';
        $clone->description = $template->description;
        $clone->is_active   = true;
        $clone->department  = $template->department;
        $clone->tags        = $template->tags;
        $clone->save();

        $question_id_lut = [];

        $num_sections  = 0;
        $num_questions = 0;

        foreach ( $template->sections as $section )
        {
            $num_sections += 1;

            $cloned_section                     = new SurveyBlueprintSection();
            $cloned_section->survey_template_id = $clone->id;
            $cloned_section->order              = $section->order;
            $cloned_section->title              = $section->title;
            $cloned_section->description        = $section->decription;
            $cloned_section->is_cloneable       = $section->is_cloneable;
            $cloned_section->save();

            foreach ( $section->questions as $question )
            {
                $num_questions += 1;

                $cloned_question                             = new SurveyBlueprintQuestion();
                $cloned_question->survey_section_template_id = $cloned_section->id;
                $cloned_question->parent_id                  = is_null( $question->parent_id ) ? null : $question_id_lut[ $question->parent_id ];
                $cloned_question->order                      = $question->order;
                $cloned_question->parent_answers             = $question->parent_answers;
                $cloned_question->question                   = $question->question;
                $cloned_question->details                    = $question->details;
                $cloned_question->additional_info_url        = $question->additional_info_url;
                $cloned_question->type                       = $question->type;
                $cloned_question->allowed_values             = $question->allowed_values;
                $cloned_question->is_answer_required         = $question->is_answer_required;
                $cloned_question->save();

                $question_id_lut[ $question->id ] = $cloned_question->id;

                foreach ( $question->children as $child_question )
                {
                    $num_questions += 1;

                    $cloned_child_question                             = new SurveyBlueprintQuestion();
                    $cloned_child_question->survey_section_template_id = $cloned_section->id;
                    $cloned_child_question->parent_id                  = is_null( $child_question->parent_id ) ? null : $question_id_lut[ $child_question->parent_id ];
                    $cloned_child_question->order                      = $child_question->order;
                    $cloned_child_question->parent_answers             = $child_question->parent_answers;
                    $cloned_child_question->question                   = $child_question->question;
                    $cloned_child_question->details                    = $child_question->details;
                    $cloned_child_question->additional_info_url        = $child_question->additional_info_url;
                    $cloned_child_question->type                       = $child_question->type;
                    $cloned_child_question->allowed_values             = $child_question->allowed_values;
                    $cloned_child_question->is_answer_required         = $child_question->is_answer_required;
                    $cloned_child_question->save();

                    $question_id_lut[ $child_question->id ] = $cloned_child_question->id;
                }
            }
        }

        alert()->success( '1 Survey, ' . $num_sections . ' Sections, ' . $num_questions . ' Questions cloned.' );

        return back();
    }

    public function start( Request $request, SurveyBlueprint $template )
    {
        $survey = $template->start();

        alert()->success( 'Template has been copied and survey created.' );

        return redirect()->route( 'surveys.invite', [
            'survey' => $survey
        ] );
    }
}
