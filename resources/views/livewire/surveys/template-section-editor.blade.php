<div style="margin-top: 50px;">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-1">
                    <button wire:click="$emit('move_section', {{$section->id}}, -1)" class="btn btn-default btn-xs"><i
                                class="glyphicon glyphicon-arrow-up"></i></button>
                    <button wire:click="$emit('move_section', {{$section->id}}, 1)" class="btn btn-default btn-xs"><i
                                class="glyphicon glyphicon-arrow-down"></i></button>
                </div>
                <div class="col-sm-10">
                    <input wire:model.lazy="section.title" class="form-control" type="text"/>
                </div>
                <div class="col-sm-1">
                    <button wire:click="$emit('delete_section_confirm', {{$section->id}})"
                            class="pull-right btn btn-danger btn-xs">delete
                    </button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12" style="text-align: center;">
                    <label><input wire:model.lazy="section.is_cloneable" type="checkbox"> Is Repeatable?</label> - Checking this box allows the survey taker to clone this section while taking the survey.
                </div>
            </div>
            <div class="alert alert-info">
                <textarea wire:model.lazy="section.description" class="form-control"
                          placeholder="section description (optional)...."></textarea>
            </div>
            <div>
                @forelse($questions as $index => $question)
                    @livewire('surveys.template-question-editor', ['question' => $question], key('Q' . $question->id))
                    <?php $children = $question->children()->orderBy('order')->get(); ?>
                    @if(count($children) > 0)
                        <div style="margin-left:50px;">
                            @foreach($children as $child_question)
                                @livewire('surveys.template-question-editor', ['question' => $child_question], key('Q' . $child_question->id))
                            @endforeach
                        </div>
                    @endif
                @empty
                    <em>No questions found.</em>
                @endforelse
            </div>

            <hr/>

            <div class="row">
                <div class="col-sm-12">
                    <div class="well">
                        <form wire:submit.prevent="add_question">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Question</label>
                                        <input wire:model.defer="new_question_question" class="form-control" required type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Description</label>
                                        <textarea wire:model.defer="new_question_details" class="form-control" rows="4"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Additional Info Url</label>
                                        <input wire:model.defer="new_question_additional_info_url" class="form-control" type="url"/>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Type</label>
                                        <select wire:model="new_question_type" class="form-control" required>
                                            @foreach(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::all() as $type)
                                                <option value="{{$type}}">{{$type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Allowed Answers <small>one per line</small></label>
                                        <?php $disabled = in_array( $new_question_type, [ \AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Checkboxes, \AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::DropDown ] ) ? '' : 'disabled'; ?>
                                        <textarea wire:model.defer="new_question_allowed_values" {{$disabled}} class="form-control" rows="4"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Required?</label>
                                        <div>
                                            <label class="radio-inline">
                                                <input wire:model.defer="new_question_is_answer_required"
                                                       name="new_question_is_answer_required" type="radio" value="1"
                                                       @if($new_question_is_answer_required) checked @endif />
                                                Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input wire:model.defer="new_question_is_answer_required"
                                                       name="new_question_is_answer_required" type="radio" value="0"
                                                       @if(!$new_question_is_answer_required) checked @endif />
                                                No
                                                <span class="radio_dot"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary">Create Question</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
@endpush
