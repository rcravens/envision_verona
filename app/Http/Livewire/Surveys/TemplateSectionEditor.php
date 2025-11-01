<?php

namespace App\Http\Livewire\Surveys;

use App\Enums\SurveyQuestionType;
use App\Models\Surveys\SurveyBlueprintQuestion;
use App\Models\Surveys\SurveyBlueprintSection;
use Livewire\Component;

class TemplateSectionEditor extends Component
{
    public $section;
    public $questions;

    public $new_question_question;
    public $new_question_details;
    public $new_question_additional_info_url;
    public $new_question_type               = SurveyQuestionType::Text;
    public $new_question_is_answer_required = true;
    public $new_question_allowed_values;

    public $delete_question;

    protected $listeners = [
        'delete_question_confirm',
        'move_question',
        'indent_question'
    ];

    public function updated( $name, $value )
    {
        $this->section->save();
    }

    public function mount( SurveyBlueprintSection $section )
    {
        $this->section = $section;

        $this->refresh_questions();
    }

    public function add_question()
    {
        $allowed_values = null;
        if ( in_array( $this->new_question_type, [
            SurveyQuestionType::Checkboxes,
            SurveyQuestionType::DropDown
        ] ) )
        {
            $data    = explode( PHP_EOL, $this->new_question_allowed_values );
            $answers = [];
            foreach ( $data as $answer )
            {
                $tmp = trim( $answer );
                if ( strlen( $tmp ) > 0 )
                {
                    $answers[] = $tmp;
                }
            }

            if ( count( $answers ) > 0 )
            {
                $allowed_values = $answers;
            }
        }

        $question                             = new SurveyBlueprintQuestion();
        $question->survey_section_template_id = $this->section->id;
        $question->parent_id                  = null;
        $question->parent_answers             = null;
        $question->order                      = SurveyBlueprintQuestion::where( 'survey_section_template_id', '=', $this->section->id )->whereNull( 'parent_id' )->count();
        $question->question                   = $this->new_question_question;
        $question->details                    = is_null( $this->new_question_details ) || strlen( $this->new_question_details ) == 0 ? null : $this->new_question_details;
        $question->additional_info_url        = is_null( $this->new_question_additional_info_url ) || strlen( $this->new_question_additional_info_url ) == 0 ? null : $this->new_question_additional_info_url;
        $question->type                       = $this->new_question_type;
        $question->allowed_values             = $allowed_values;
        $question->is_answer_required         = $this->new_question_is_answer_required;

        $question->save();

        $this->new_question_question            = '';
        $this->new_question_details             = '';
        $this->new_question_additional_info_url = '';
        $this->new_question_type                = SurveyQuestionType::Text;
        $this->new_question_is_answer_required  = true;
        $this->new_question_allowed_values      = '';

        $this->refresh_questions();
    }

    public function delete_question_confirm( $question_id )
    {
        $this->delete_question = $this->section->questions()->where( 'id', '=', $question_id )->first();
        if ( ! is_null( $this->delete_question ) )
        {
            $this->emit( 'show_delete_question_confirmation_modal', $this->section->id );
        }
    }

    public function delete_question()
    {
        if ( is_null( $this->delete_question ) )
        {
            return;
        }

        $parent_id = $this->delete_question->parent_id;
        $order     = $this->delete_question->order;

        $this->delete_question->delete();

        // Now need to bump questions below this one up one
        //
        $questions_to_bump_up = $this->section->questions()
                                              ->where( 'parent_id', '=', $parent_id )
                                              ->where( 'order', '>=', $order )
                                              ->get();
        foreach ( $questions_to_bump_up as $question )
        {
            $question->order = $question->order - 1;
            $question->save();
        }
        $this->refresh_questions();
    }

    public function move_question( $question_id, $direction )
    {
        $question_to_move = $this->section->questions()
                                          ->where( 'id', '=', $question_id )
                                          ->first();
        if ( is_null( $question_to_move ) )
        {
            return;
        }

        if ( ! in_array( $direction, [
            1,
            - 1
        ] ) )
        {
            return;
        }

        $question_to_swap_with = $this->section->questions()
                                               ->where( 'parent_id', '=', $question_to_move->parent_id )
                                               ->where( 'order', '=', $question_to_move->order + $direction )
                                               ->first();;
        if ( is_null( $question_to_swap_with ) )
        {
            return;
        }

        $my_order                = $question_to_move->order;
        $question_to_move->order = $question_to_swap_with->order;
        $question_to_move->save();

        $question_to_swap_with->order = $my_order;
        $question_to_swap_with->save();

        $this->refresh_questions();
    }

    public function indent_question( $question_id, $direction )
    {
        switch ( $direction )
        {
            case 1:
                $this->becoming_a_child( $question_id );
                break;
            case - 1:
                $this->leaving_a_parent( $question_id );
                break;
            default:
                return;
        }

        $this->refresh_questions();

        $this->emit( 'refresh_question_' . $question_id );
    }

    public function render()
    {
        return view( 'livewire.surveys.template-section-editor' );
    }

    protected function getListeners()
    {
        return [
            'delete_question_confirm_' . $this->section->id => 'delete_question_confirm',
            'delete_question_' . $this->section->id         => 'delete_question',
            'move_question_' . $this->section->id           => 'move_question',
            'indent_question_' . $this->section->id         => 'indent_question'
        ];
    }

    protected function rules()
    {
        return [
            'section.title'                    => 'required|string',
            'section.is_cloneable'             => 'required|boolean',
            'section.description'              => 'string',
            'new_question_question'            => 'string',
            'new_question_details'             => 'nullable|string',
            'new_question_additional_info_url' => 'nullable|string',
            'new_question_type'                => 'string',
            'new_question_is_answer_required'  => 'boolean',
            'new_question_allowed_values'      => 'nullable|string'
        ];
    }

    private function becoming_a_child( $question_id )
    {
        $question_to_become_child = $this->section->questions()
                                                  ->whereNull( 'parent_id' )
                                                  ->where( 'id', '=', $question_id )
                                                  ->first();
        if ( is_null( $question_to_become_child ) )
        {
            return;
        }

        $my_parent = $this->section->questions()
                                   ->whereNull( 'parent_id' )
                                   ->where( 'order', '=', $question_to_become_child->order - 1 )
                                   ->first();
        if ( is_null( $my_parent ) )
        {
            return;
        }

        $my_current_order = $question_to_become_child->order;

        // Move the question to a child question
        //
        $current_child_count                 = $my_parent->children()->count();
        $question_to_become_child->parent_id = $my_parent->id;
        $question_to_become_child->order     = $current_child_count;
        $question_to_become_child->save();

        // Fill the gap I left by moving questions below me up
        //
        $questions_to_move_up = $this->section->questions()
                                              ->whereNull( 'parent_id' )
                                              ->where( 'order', '>', $my_current_order )
                                              ->get();
        foreach ( $questions_to_move_up as $question )
        {
            $question->order = $question->order - 1;
            $question->save();
        }
    }

    private function leaving_a_parent( $question_id )
    {
        $question_leaving_parent = SurveyQuestionTemplate::find( $question_id );
        if ( is_null( $question_leaving_parent ) || $question_leaving_parent->survey_section_template_id != $this->section->id || is_null( $question_leaving_parent->parent_id ) )
        {
            return;
        }

        $my_parent = $this->section->questions()
                                   ->where( 'id', '=', $question_leaving_parent->parent_id )
                                   ->first();
        if ( is_null( $my_parent ) )
        {
            return;
        }

        $my_current_order = $question_leaving_parent->order;

        // Move the question to the end of the section
        //
        $current_count                           = $this->section->questions()->whereNull( 'parent_id' )->count();
        $question_leaving_parent->parent_id      = null;
        $question_leaving_parent->parent_answers = null;
        $question_leaving_parent->order          = $current_count;
        $question_leaving_parent->save();

        // Fill the gap I left in my parent by moving out
        //
        $questions_to_move_up = $this->section->questions()
                                              ->where( 'parent_id', '=', $my_parent->id )
                                              ->where( 'order', '>', $my_current_order )
                                              ->get();
        foreach ( $questions_to_move_up as $question )
        {
            $question->order = $question->order - 1;
            $question->save();
        }
    }

    private function refresh_questions()
    {
        $this->questions = $this->section->questions()->with( [
                                                                  'children' => function ( $query ) {
                                                                      return $query->orderBy( 'order' );
                                                                  }
                                                              ] )->orderBy( 'order' )->get();
    }
}
