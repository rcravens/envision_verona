<?php

namespace App\Http\Livewire\Surveys;

use App\Enums\SurveyQuestionType;
use App\Models\Surveys\SurveyBlueprintQuestion;
use Illuminate\Support\Str;
use Livewire\Component;

class TemplateQuestionEditor extends Component
{
    public $question;
    public $allowed_values_text;
    public $parent_answers;

    public function mount( SurveyBlueprintQuestion $question )
    {
        $this->question = $question;

        $this->init();
    }

    public function reload_question()
    {
        $this->question = $this->question->refresh();

        $this->init();

        if ( count( $this->parent_answers ) == 0 && ! is_null( $this->question->parent_answers ) )
        {
            $this->question->parent_answers = null;
            $this->question->save();
        }
    }

    public function updated( $name, $value )
    {
        if ( Str::startsWith( $name, 'parent_answers.' ) )
        {
            $name = str_replace( 'parent_answers.', '', $name );
            if ( array_key_exists( $name, $this->parent_answers ) )
            {
                if ( $value === false )
                {
                    // Removing this from the values
                    //
                    $this->parent_answers[ $name ] = false;
                }
                else
                {
                    // Adding this to the array
                    //
                    $this->parent_answers[ $name ] = true;
                }
            }

            $parent_answers = [];
            foreach ( $this->parent_answers as $name => $is_checked )
            {
                if ( $is_checked )
                {
                    $parent_answers[] = $name;
                }
            }

            $this->question->parent_answers = count( $parent_answers ) > 0 ? $parent_answers : null;
        }

        if ( $name == 'allowed_values_text' )
        {
            $allowed_values = null;
            if ( strlen( trim( $value ) ) > 0 )
            {
                $values         = explode( PHP_EOL, trim( $value ) );
                $allowed_values = [];
                foreach ( $values as $tmp )
                {
                    $tmp = trim( $tmp );
                    if ( strlen( $tmp ) > 0 )
                    {
                        $allowed_values[] = $tmp;
                    }
                }
            }
            $this->question->allowed_values = $allowed_values;
        }

        if ( in_array( $name, [
            'question.type',
            'allowed_values_text'
        ] ) )
        {
            foreach ( $this->question->children as $child_question )
            {
                $this->emit( 'refresh_question_' . $child_question->id );
            }
        }

        if ( ! in_array( $this->question->type, [
            SurveyQuestionType::Checkboxes,
            SurveyQuestionType::DropDown
        ] ) )
        {
            $this->question->allowed_values = null;
            $this->allowed_values_text      = '';
        }

        $this->question->save();
    }

    public function render()
    {
        return view( 'livewire.surveys.template-question-editor' );
    }

    protected function rules()
    {
        return [
            'question.question'            => 'required|string',
            'question.details'             => 'nullable|string',
            'question.additional_info_url' => 'nullable|string',
            'question.type'                => 'string',
            'question.is_answer_required'  => 'boolean',
            'question.allowed_values'      => 'nullable|string',
            'parent_answers_checkbox.*'    => 'nullable',
            'allowed_values_text'          => 'nullable|string'
        ];
    }

    protected function getListeners()
    {
        return [
            'refresh_question_' . $this->question->id => 'reload_question'
        ];
    }

    private function init()
    {
        $this->allowed_values_text = is_null( $this->question->allowed_values ) ? '' : implode( PHP_EOL, $this->question->allowed_values );
        $this->parent_answers      = [];
        if ( ! is_null( $this->question->parent_id ) )
        {
            $desired_parent_answers = is_null( $this->question->parent_answers ) ? [] : $this->question->parent_answers;

            $parent = $this->question->parent;
            switch ( $parent->type )
            {
                case SurveyQuestionType::Checkboxes:
                case SurveyQuestionType::DropDown:
                    if ( is_array( $parent->allowed_values ) )
                    {
                        foreach ( $parent->allowed_values as $value )
                        {
                            $this->parent_answers[ $value ] = in_array( $value, $desired_parent_answers );
                        }
                    }
                    break;
                case SurveyQuestionType::Boolean:
                    $this->parent_answers[ "No" ]  = in_array( "No", $desired_parent_answers );
                    $this->parent_answers[ "Yes" ] = in_array( "Yes", $desired_parent_answers );
                    break;
                case SurveyQuestionType::Audit:
                    $this->parent_answers[ 'Met' ]            = in_array( 'Met', $desired_parent_answers );
                    $this->parent_answers[ 'Not Met' ]        = in_array( 'Not Met', $desired_parent_answers );
                    $this->parent_answers[ 'Not Applicable' ] = in_array( 'Not Applicable', $desired_parent_answers );
            }
        }
    }
}
