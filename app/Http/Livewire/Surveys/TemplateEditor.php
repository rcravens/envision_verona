<?php

namespace App\Http\Livewire\Surveys;

use App\Models\Surveys\SurveyBlueprint;
use App\Models\Surveys\SurveyBlueprintSection;
use Livewire\Component;

class TemplateEditor extends Component
{
    public $blueprint;
    public $sections;

    public $new_section_title;
    public $new_section_description;
    public $new_section_is_cloneable;

    public $delete_section = null;

    protected $listeners = [
        'delete_section_confirm',
        'move_section'
    ];

    public function mount( SurveyBlueprint $blueprint )
    {
        $this->blueprint = $blueprint;

        $this->refresh_sections();
    }

    public function save_survey()
    {
        $this->blueprint->save();
    }

    public function add_section()
    {
        $section                     = new SurveyBlueprintSection();
        $section->survey_template_id = $this->blueprint->id;
        $section->title              = $this->new_section_title;
        $section->description        = $this->new_section_description;
        $section->is_cloneable       = is_null( $this->new_section_is_cloneable ) ? false : $this->new_section_is_cloneable;
        $section->order              = SurveyBlueprintSection::where( 'survey_blueprint_id', '=', $this->blueprint->id )->count();

        $section->save();

        $this->refresh_sections();

        $this->new_section_title       = '';
        $this->new_section_description = '';
    }

    public function delete_section_confirm( $section_id )
    {
        $this->delete_section = $this->blueprint->sections()->where( 'id', '=', $section_id )->first();

        if ( ! is_null( $this->delete_section ) )
        {
            $this->emit( 'show_delete_section_confirmation_modal' );
        }
    }

    public function delete_section()
    {
        $order = $this->delete_section->order;

        $this->delete_section->delete();

        // Now need to bump sections below this one up one
        //
        $sections_to_bump_up = $this->blueprint->sections()->where( 'order', '>=', $order )->get();
        foreach ( $sections_to_bump_up as $section )
        {
            $section->order = $section->order - 1;
            $section->save();
        }
        $this->refresh_sections();
    }

    public function move_section( $section_id, $direction )
    {
        $section_to_move = $this->blueprint->sections()->where( 'id', '=', $section_id )->first();
        if ( is_null( $section_to_move ) )
        {
            return;
        }

        $section_to_swap_with = null;
        if ( $direction == 1 )
        {
            // Moving down...swap with the section below me
            //
            $section_to_swap_with = $this->blueprint->sections()->where( 'order', '=', $section_to_move->order + 1 )->first();
        }
        if ( $direction == - 1 )
        {
            // Moving up....swap with the section above me
            //
            $section_to_swap_with = $this->blueprint->sections()->where( 'order', '=', $section_to_move->order - 1 )->first();
        }
        if ( is_null( $section_to_swap_with ) )
        {
            return;
        }

        $my_order               = $section_to_move->order;
        $section_to_move->order = $section_to_swap_with->order;
        $section_to_move->save();

        $section_to_swap_with->order = $my_order;
        $section_to_swap_with->save();

        $this->refresh_sections();
    }

    public function render()
    {
        return view( 'livewire.surveys.template-editor' );
    }

    protected function rules()
    {
        return [
            'template.title'           => 'required|string',
            'template.description'     => 'string',
            'new_section_title'        => 'required|string',
            'new_section_description'  => 'string',
            'new_section_is_cloneable' => 'boolean'
        ];
    }

    private function refresh_sections()
    {
        $this->sections = $this->blueprint->sections()->orderBy( 'order' )->get();
    }
}
