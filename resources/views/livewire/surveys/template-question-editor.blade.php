<div>
    <div class="well">
        <div class="row">
            <div class="col-sm-1">
                @if(is_null($question->parent_id) && $question->order != 0)
                    <button wire:click="$emit('indent_question_{{$question->survey_section_template_id}}', {{$question->id}}, 1)" class="btn btn-default btn-xs" style="float:right;top:13px;position:relative;padding:1px 3px;"><i class="glyphicon glyphicon-arrow-right"></i></button>
                @endif
                @if(!is_null($question->parent_id))
                    <button wire:click="$emit('indent_question_{{$question->survey_section_template_id}}', {{$question->id}}, -1)" class="btn btn-default btn-xs" style="float:right;top:13px;position:relative;padding:1px 3px;"><i class="glyphicon glyphicon-arrow-left"></i></button>
                @endif

                <button wire:click="$emit('move_question_{{$question->survey_section_template_id}}', {{$question->id}}, -1)" class="btn btn-default btn-xs" style="padding:1px 3px;"><i class="glyphicon glyphicon-arrow-up"></i></button>
                <button wire:click="$emit('move_question_{{$question->survey_section_template_id}}', {{$question->id}}, 1)" class="btn btn-default btn-xs" style="padding:1px 3px;"><i class="glyphicon glyphicon-arrow-down"></i></button>
            </div>
            <div class="col-sm-10">
				<?php
				$allowed_values = null;
				if ( !is_null( $question->parent_id ) )
				{
					switch ( $question->parent->type )
					{
						case \AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Checkboxes:
						case \AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::DropDown:
							if(!is_null($question->parent->allowed_values) && is_array($question->parent->allowed_values))
                            {
                                foreach ( $question->parent->allowed_values as $value )
                                {
                                    $allowed_values[ $value ] = $value;
                                }
							}
							break;
						case \AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Boolean:
							$allowed_values = [ "Yes" => 'Yes', "No" => 'No' ];
							break;
						case \AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Audit:
							$allowed_values = [ 'Met' => 'Met', 'Not Met' => 'Not Met', 'Not Applicable' => 'Not Applicable'];
							break;
					}
				}
				?>
                @if(!is_null($allowed_values))
                    <div class="alert alert-warning">
                        Only Enable For Checked Values:
                        @foreach($allowed_values as $value => $text)
                            <label style="margin:0 6px;"><input wire:model="parent_answers.{{$value}}" name="parent_answers" value="1" type="checkbox"/>{{$text}}</label>
                        @endforeach
                    </div>
                @endif
                <input wire:model.debounce.500ms="question.question" class="form-control"/>
            </div>
            <div class="col-sm-1">
                <button wire:click="$emit('delete_question_confirm_{{$question->survey_section_template_id}}', {{$question->id}})" class="pull-right btn btn-danger btn-xs">delete</button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <textarea wire:model.debounce.500ms="question.details" class="form-control" placeholder="detail (optional)"></textarea>
                <input wire:model.debounce.500ms="question.additional_info_url" type="url" class="form-control" placeholder="https://optional_url.com"/>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Type</label>
                    <select wire:model="question.type" class="form-control" required>
                        @foreach(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::all() as $type)
                            <option value="{{$type}}">{{$type}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Required?</label>
                    <div>
                        <label class="radio-inline">
                            <input wire:model="question.is_answer_required" name="is_answer_required_{{$question->id}}" type="radio" value="1" @if($question->is_answer_required) checked @endif />
                            Yes
                        </label>
                        <label class="radio-inline">
                            <input wire:model="question.is_answer_required" name="is_answer_required_{{$question->id}}" type="radio" value="0" @if(!$question->is_answer_required) checked @endif />
                            No
                            <span class="radio_dot"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Allowed Answers <small>one per line</small></label>
					<?php $disabled = in_array( $question->type, [ \AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Checkboxes, \AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::DropDown ] ) ? '' : 'disabled'; ?>
                    <textarea wire:model.debounce.500ms="allowed_values_text" {{$disabled}} class="form-control" rows="4"></textarea>
                </div>
            </div>

        </div>
    </div>
</div>
