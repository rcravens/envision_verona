@switch($question->type)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::File)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Photo)
        File Upload
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::StaffMember)
        Staff Member
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Checkboxes)
        @if(!is_null($question->allowed_values))
            Select Many: {{implode(', ', $question->allowed_values)}}
        @endif
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::DropDown)
        Select One: {{implode(', ', $question->allowed_values)}}
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::TextPlus)
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Text)
        Text
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Url)
        URL
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Date)
        Date
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Email)
        Email
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Boolean)
        Select One: Yes, No
        @break
    @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Audit)
        Select One: Met, Not Met, Not Applicable
        @break
    @default
        {{$question->type}}
@endswitch
