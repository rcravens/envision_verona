@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="/css/surveys.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <span class="pull-right">
                        <a href="{{route('surveys.index')}}" class="btn btn-default btn-xs">&laquo; All Surveys</a>
                        @if(!is_null($audit))
                            <a href="{{route('audits.show', [$audit->id])}}" class="btn btn-default btn-xs">&laquo; Audit</a>
                        @endif
                        @if(is_null($audit))
                            @if(\Illuminate\Support\Facades\Auth::user()->can_be_any([\AssetIQ\Models\SecurityRoleOptions::SurveyAdmin, \AssetIQ\Models\SecurityRoleOptions::OperationalViewer, \AssetIQ\Models\SecurityRoleOptions::OperationalAdmin]))
                                <a href="{{route('surveys.invite', [$survey->id])}}" class="btn btn-default btn-xs"><i class="fa fa-users"></i> Invites</a>
                            @endif
                        @else
                            @if(\Illuminate\Support\Facades\Auth::user()->can_be_any([\AssetIQ\Models\SecurityRoleOptions::AuditAdmin, \AssetIQ\Models\SecurityRoleOptions::OperationalViewer, \AssetIQ\Models\SecurityRoleOptions::OperationalAdmin]))
                                <a href="{{route('audits.invite', [$audit->id])}}" class="btn btn-default btn-xs"><i class="fa fa-users"></i> Invites</a>
                            @endif
                        @endif
                        <a href="{{route('surveys.show', [$survey->id])}}" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-stats"></i> Dashboard</a>
                    </span>
                    Survey Results - {{$survey->title}}
                </div>

                <div class="panel-body">

                    @if(!is_null($survey->description))
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="alert alert-info">
                                    {{$survey->description}}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-sm-12">
                            <a href="{{route('surveys.show', [$survey->id])}}" class="btn btn-link"><i class="glyphicon glyphicon-th-large"></i> Summary</a>
                            <a href="{{route('surveys.details', [$survey->id])}}" class="btn btn-link"><i class="glyphicon glyphicon-th"></i> Detail</a>
                            <a href="{{route('surveys.answers.table', [$survey->id])}}" class="btn btn-link"><i class="glyphicon glyphicon-th-list"></i> List</a>
                            @if(!is_null($audit))
                                <select id="location_filter" class="form-control" style="display: inline; width: 200px;">
                                    <option value="">-- All Locations --</option>
                                    @foreach($location_lut as $code => $location)
                                        <?php $selected = $selected_location_code == $code ? 'selected="selected"' : ''; ?>
                                        <option value="{{$code}}" {{$selected}}>{{$location->code . ' - ' . $location->city . ', ' . $location->state}}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="questions col-sm-8 col-sm-offset-2">
                            @foreach($survey->sections as $section)
                                <?php $copies = $section->copies()->orderBy('copy_number')->get(); ?>
                                <?php $copy_ids = $copies->pluck('id')->toArray(); ?>
                                <div class="panel panel-primary section">
                                    <div class="panel-heading">
                                        <span class="pull-right">{{count($section->questions)}} questions</span>
                                        {{$section->title}}
                                    </div>
                                    <div class="panel-body">
                                        @if(!is_null($section->description) && strlen(trim($section->description)) > 0)
                                            <div class="alert alert-info">{{$section->description}}</div>
                                        @endif
                                        @foreach($section->questions as $index => $question)
                                            <?php
                                                $all_answers = [];
												foreach($survey_targets as $target)
                                                {
													$answers = array_key_exists($target->id, $question->answers_by_target_lut) ? $question->answers_by_target_lut[$target->id] : null;
													if(!is_null($answers))
                                                    {
	                                                    $all_answers = array_merge($all_answers, $answers);
                                                    }
                                                }
                                            ?>
                                            <div class="question">
                                                <h4 class="question_header lead">{{$index + 1}}. {{$question->question}}</h4>

                                                <div class="question_summary @if(count($question->children)>0) parent_question @endif">
                                                    @include('survey._detail_table', ['question' => $question, 'answers' => $all_answers])
                                                </div>
                                                @foreach($question->children as $child_index => $child)
                                                    <?php
                                                        $all_answers = [];
                                                        foreach($survey_targets as $target)
                                                        {
                                                            $answers = array_key_exists($target->id, $child->answers_by_target_lut) ? $child->answers_by_target_lut[$target->id] : null;
															if(!is_null($answers))
                                                            {
                                                                $all_answers = array_merge($all_answers, $answers);
                                                            }
                                                        }
                                                    ?>
                                                    <div class="child_question">
                                                        <h4 class="question_header lead">
                                                            {{chr(ord('A') + $child_index)}}. {{$child->question}}
                                                            <small>({{is_null($child->parent_answers) ? '-- none selected --' : implode(', ', $child->parent_answers)}})</small>
                                                        </h4>

                                                        <div class="question_summary">
                                                            @include('survey._detail_table', ['question' => $child, 'answers' => $all_answers])
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="application/javascript">
        (function ($) {
            $(document).ready(function () {

                @if(!is_null($audit))
                    $('#location_filter').change(function(){
                        filter_location();
                    });
                    filter_location();

                    function filter_location(){
                        var code = $('#location_filter').val();
                        if(code === ''){
                            $('tr.location').show();
                        } else {
                            $('tr.location').hide();
                            $('tr.location.' + code).show();
                        }

                    }
                @endif
            });
        })(jQuery);
    </script>
@endpush
