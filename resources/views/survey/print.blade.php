@extends('layouts.app_no_topnav_fullwidth')

@push('styles')
    <link rel="stylesheet" type="text/css" href="/css/surveys.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    Survey - {{$survey->title}}
                </div>

                <div class="panel-body">

                    @if($survey->status == \AssetIQ\Models\Surveys\SurveyStatusOptions::Stopped)
                        <div class="alert alert-danger">
                            This survey has been closed.
                        </div>
                    @endif

                    @if(is_null($target))
                        <div class="alert alert-danger">Sorry, this survey is not assigned to a taker.</div>
                    @else
                        @if($target->is_survey_completed())
                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <div class="alert alert-success">
                                        Survey completed on {{$target->last_viewed_at->toDateString()}}.
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            @if(is_null($audit_target))
                                <div class="col-sm-8 col-sm-offset-2" style="text-align: center;">
                                    This survey is for <strong>{{$target->first_name}} {{$target->last_name}}</strong>.
                                </div>
                            @else
                                <div class="col-sm-8 col-sm-offset-2" style="text-align: center;">
                                    This audit is for <strong>{{$audit_target->location->code}} - {{$audit_target->location->city}}, {{$audit_target->location->state}}</strong> to be completed by <strong>{{$target->first_name}} {{$target->last_name}}</strong>.
                                    <br /><br />
                                    Audit Title: <strong>{{$audit_target->audit->template->title}}</strong><br />
                                    Created By: <strong>{{$audit_target->audit->created_by->first_name}} {{$audit_target->audit->created_by->last_name}}</strong>
                                </div>
                            @endif
                        </div>

                        @if(!is_null($survey->description) && strlen(trim($survey->description)) > 0)
                            <div class="row" style="margin-top: 30px;">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <div class="alert alert-info">
                                        {{$survey->description}}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <?php $can_see_answers = \Illuminate\Support\Facades\Auth::hasUser() && (\Illuminate\Support\Facades\Auth::user()->can_be_any([\AssetIQ\Models\SecurityRoleOptions::AuditAdmin, \AssetIQ\Models\SecurityRoleOptions::SurveyAdmin, \AssetIQ\Models\SecurityRoleOptions::OperationalAdmin, \AssetIQ\Models\SecurityRoleOptions::OperationalViewer]) || \Illuminate\Support\Facades\Auth::user()->id == $target->user_id) ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <table id="my_table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            @if($is_clonable_used)
                                                <th>Clonable</th>
                                            @endif
                                            @if($is_description_used)
                                                <th>Description</th>
                                            @endif
                                            <th>Question</th>
                                            @if($is_details_used)
                                                <th>Details</th>
                                            @endif
                                            @if($is_url_used)
                                                <th>URL</th>
                                            @endif
                                            <th>Required?</th>
                                            <th>Hint</th>
                                            <th style="width:200px;">Answer</th>
                                        </tr>
                                    </thead>
                                    @foreach($survey->sections as $section)
                                        @foreach($section->copies as $copy)
                                            @foreach($copy->questions as $index => $question)
                                                <tr>
                                                    <td>{{$section->title}}</td>
                                                    @if($is_clonable_used)
                                                        <td>{{$section->is_cloneable ? 'YES' : 'NO'}}</td>
                                                    @endif
                                                    @if($is_description_used)
                                                        <td>{{$section->description}}</td>
                                                    @endif
                                                    <td>{{$index + 1}}. {{$question->question}}</td>
                                                    @if($is_details_used)
                                                        <td>{{$question->details}}</td>
                                                    @endif
                                                    @if($is_url_used)
                                                        <td>{{$question->additional_info_url}}</td>
                                                    @endif
                                                    <td>{{$question->is_answer_required ? 'YES' : 'NO'}}</td>
                                                    <td>@include('survey._question_hint_print', ['question' => $question])</td>
                                                    <td>
                                                        @if($can_see_answers)
                                                            @include('survey._question_answer_print', ['question' => $question])
                                                        @endif
                                                    </td>
                                                </tr>
                                                @foreach($question->children as $child_index => $child)
                                                    <tr>
                                                        <td>{{$section->title}}</td>
                                                        @if($is_clonable_used)
                                                            <td>{{$section->is_cloneable ? 'YES' : 'NO'}}</td>
                                                        @endif
                                                        @if($is_description_used)
                                                            <td>{{$section->description}}</td>
                                                        @endif
                                                        <td>{{chr(ord('A') + $child_index)}}. {{$child->question}}</td>
                                                        @if($is_details_used)
                                                            <td>{{$question->details}}</td>
                                                        @endif
                                                        @if($is_url_used)
                                                            <td>{{$question->additional_info_url}}</td>
                                                        @endif
                                                        <td>{{$question->is_answer_required ? 'YES' : 'NO'}}</td>
                                                        <td>@include('survey._question_hint_print', ['question' => $child])</td>
                                                        <td>
                                                            @if($can_see_answers)
                                                                @include('survey._question_answer_print', ['question' => $child])
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {

                $('#my_table').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'copy',
                            exportOptions: {
                                columns: 'thead th:not(.no_export)'
                            }
                        },
                        {
                            extend: 'csv',
                            exportOptions: {
                                columns: 'thead th:not(.no_export)'
                            }
                        },
                        {
                            extend:  'excel',
                            exportOptions: {
                                columns: 'thead th:not(.no_export)'
                            }
                        },
                        {
                            extend:  'print',
                            exportOptions: {
                                columns: 'thead th:not(.no_export)'
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            orientation: 'landscape',
                            text:'PDF-Landscape',
                        }
                    ],
                    paging: false,
                    fixedHeader: true,
                });
            });
        })(jQuery);
    </script>
@endpush