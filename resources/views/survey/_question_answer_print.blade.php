<?php $answer = is_null($question->answer) ? [''] : $question->answer->answer; ?>
@switch($question->type)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::File)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Photo)
        <?php $file = is_null($question->answer) ? null : $question->answer->file(); ?>
        @if(!is_null($file))
            @if($file->is_image())
                Image: {{$file->name}} - {{$file->download_inline_url}}
            @else
                File: {{$file->name}} - {{$file->download_url}}
            @endif
        @endif
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Checkboxes)
        @if(!is_null($question->allowed_values))
            {{implode(', ', $answer)}}
        @endif
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::StaffMember)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::DropDown)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::TextPlus)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Text)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Url)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Date)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Email)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Boolean)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Audit)
    @default
        {{implode(', ', $answer)}}
@endswitch
