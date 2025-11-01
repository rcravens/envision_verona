<table class="table table-condensed table-striped">
    <thead>
        <tr>
            @if(!is_null($audit))
                <th>Location</th>
            @endif
            <th>Person</th>
            <th>Answer</th>
        </tr>
    </thead>
    <tbody>
        @foreach($answers as $answer)
            <?php $audit_target = array_key_exists($answer->target->id, $audit_target_lut) ? $audit_target_lut[$answer->target->id] : null; ?>
            <tr class="location {{is_null($audit_target) ? '' : $audit_target->location->code}}">
                @if(!is_null($audit))
                    <td>{{is_null($audit_target) ? '--' : $audit_target->location->code}}</td>
                @endif
                <td>{{$answer->target->last_name . ', ' . $answer->target->first_name}}</td>
                <td>
                    @switch($question->type)
                        @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::StaffMember)
                            <?php $staff_member = \AssetIQ\Models\User::find($answer->answer[0]); ?>
                            {{is_null($staff_member) ? 'not found' : $staff_member->last_name . ', ' . $staff_member->first_name}}
                            @break
                        @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::Photo)
                        @case(\AssetIQ\Models\Surveys\SurveyQuestionTypeOptions::File)
                            <?php $file = $answer->file(); ?>
                            @if(!is_null($file))
                                @if($file->is_image())
                                    <img src="{{$file->download_inline_url}}" title="{{$file->name}}" style="width:80%;" /><br />
                                @endif
                                <a href="{{$file->download_url}}" target="_blank" style="margin-top:10px;display:inline-block"><i class="glyphicon glyphicon-download"></i> {{$file->name}}</a>
                            @else
                                resource not found
                            @endif
                            @break
                        @default
                        {!! implode('<br/>', $answer->answer) !!}
                    @endswitch
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
