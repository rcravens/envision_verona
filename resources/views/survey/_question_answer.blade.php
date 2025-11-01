@if( !is_null($question->details) && strlen(trim($question->details))>0)
    <div class="row" style="margin-bottom:20px;">
        <div class="col-sm-10 col-sm-offset-1">
            <em>
                {{$question->details}}
            </em>
        </div>
    </div>
@endif

@if( !is_null($question->additional_info_url) && strlen(trim($question->additional_info_url))>0)
    <div class="row" style="margin-bottom:20px;">
        <div class="col-sm-10 col-sm-offset-1">
            &nbsp;
            <div class="pull-right">
                <a href="{{$question->additional_info_url}}" target="_blank">additional information</a>
            </div>
        </div>
    </div>
@endif

<?php $answer = is_null($question->answer) ? [''] : $question->answer->answer; ?>
<?php $required = $question->is_answer_required ? 'required' : ''; ?>
@switch($question->type)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::File)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Photo)
        <input form="survey_main" class="form-control survey_file" data-qid="{{$question->id}}" data-cid="{{$copy->id}}" type="file" name="c-{{$copy->id}}_q-{{$question->id}}" {{$required}}  />
        <div id="file_q_{{$question->id}}_c_{{$copy->id}}" style="padding:10px;text-align: center;">
            <?php $file = is_null($question->answer) ? null : $question->answer->file(); ?>
            @if(!is_null($file))
                @if($file->is_image())
                    <img src="{{$file->download_inline_url}}" title="{{$file->name}}" style="width:80%;" /><br />
                @endif
                <a href="{{$file->download_url}}" target="_blank" style="margin-top:10px;display:inline-block"><i class="glyphicon glyphicon-download"></i> {{$file->name}}</a>
            @endif
        </div>
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::StaffMember)
        <?php $attributes = ['data-qid' => $question->id, 'data-cid' => $copy->id, 'form' => 'survey_main']; ?>
        {{\AssetIQ\ProjectX\Html\UserSelector::to_html('c-' . $copy->id . '_q-' . $question->id, $answer[0], $question->is_answer_required, $attributes)}}
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Checkboxes)
        @if(!is_null($question->allowed_values))
            @foreach($question->allowed_values as $value)
                <label class="survey_checkbox">
                    <?php $checked = in_array($value, $answer) ? 'checked="checked"' : ''; ?>
                    <input form="survey_main" type="checkbox" data-qid="{{$question->id}}" data-cid="{{$copy->id}}" name="c-{{$copy->id}}_q-{{$question->id}}[]" value="{{$value}}" {{$checked}} />
                    {{$value}}
                    <span class="checkbox_check"></span>
                </label>
            @endforeach
        @endif
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::DropDown)
        <select form="survey_main" class="form-control" data-qid="{{$question->id}}" data-cid="{{$copy->id}}" name="c-{{$copy->id}}_q-{{$question->id}}" {{$required}}>
            <option value="">--- select one ---</option>
            @if(!is_null($question->allowed_values))
                @foreach($question->allowed_values as $value)
                    <?php $selected = $value==$answer[0] ? 'selected="selected"' : ''; ?>
                    <option value="{{$value}}" {{$selected}}>{{$value}}</option>
                @endforeach
            @endif
        </select>
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::TextPlus)
        <textarea form="survey_main" maxlength="2000" class="form-control" data-qid="{{$question->id}}" data-cid="{{$copy->id}}" name="c-{{$copy->id}}_q-{{$question->id}}" {{$required}}>{{$answer[0]}}</textarea>
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Text)
        <input form="survey_main" maxlength="2000" class="form-control" data-qid="{{$question->id}}" data-cid="{{$copy->id}}" type="text" name="c-{{$copy->id}}_q-{{$question->id}}" {{$required}} value="{{$answer[0]}}" />
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Url)
        <input form="survey_main" maxlength="2000" class="form-control" data-qid="{{$question->id}}" data-cid="{{$copy->id}}" type="url" name="c-{{$copy->id}}_q-{{$question->id}}" placeholder="https://example.com" {{$required}} value="{{$answer[0]}}" />
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Date)
        <input form="survey_main" class="form-control" data-qid="{{$question->id}}" data-cid="{{$copy->id}}" style="width:50%;" type="date" name="c-{{$copy->id}}_q-{{$question->id}}" placeholder="" {{$required}} value="{{$answer[0]}}" />
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Email)
        <input form="survey_main" maxlength="2000" class="form-control" data-qid="{{$question->id}}" data-cid="{{$copy->id}}" type="email" name="c-{{$copy->id}}_q-{{$question->id}}" placeholder="user@example.com" {{$required}} value="{{$answer[0]}}" />
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Boolean)
        <?php $response_options = ['Yes', 'No']; ?>
        @foreach($response_options as $option)
            <label class="survey_radio">
                <input form="survey_main" type="radio" data-qid="{{$question->id}}" data-cid="{{$copy->id}}" name="c-{{$copy->id}}_q-{{$question->id}}" value="{{$option}}" {{$required}} @if($answer[0] == $option) checked="checked" @endif />
                {{$option}}
                <span class="radio_dot"></span>
            </label>
        @endforeach
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Audit)
        <?php $response_options = ['Met', 'Not Met', 'Not Applicable']; ?>
        @foreach($response_options as $option)
            <label class="survey_radio">
                <input form="survey_main" type="radio" data-qid="{{$question->id}}" data-cid="{{$copy->id}}" name="c-{{$copy->id}}_q-{{$question->id}}" value="{{$option}}" {{$required}} @if($answer[0] == $option) checked="checked" @endif />
                {{$option}}
                <span class="radio_dot"></span>
            </label>
        @endforeach
        @break
    @default
        {{$question->type}}
@endswitch
