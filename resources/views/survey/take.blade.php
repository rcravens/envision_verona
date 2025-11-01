@extends('layouts.app_no_topnav')

@push('styles')
    <link rel="stylesheet" type="text/css" href="/css/surveys.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <span class="pull-right">
                        <a href="{{route('survey.print', [$survey->hash, $target->hash])}}" target="_blank">print</a>
                    </span>
                    Survey - {{$survey->title}}
                </div>

                <div class="panel-body">

                    @if(!is_null($target) && $target->is_survey_completed())
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <div class="alert alert-success">
                                    Thank you! You completed this survey on {{$target->last_viewed_at->toDateString()}}.
                                </div>
                            </div>
                        </div>
                    @else
                        @if(is_null($target) || $survey->status == \AssetIQ\Models\Surveys\SurveyStatusOptions::Started)

                            @if(!is_null($target))
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
                            @endif

                            @if(!is_null($survey->description) && strlen(trim($survey->description)) > 0)
                                <div class="row" style="margin-top: 30px;">
                                    <div class="col-sm-8 col-sm-offset-2">
                                        <div class="alert alert-info">
                                            {{$survey->description}}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="questions col-sm-8 col-sm-offset-2">
                                    @foreach($survey->sections as $section)
                                        @foreach($section->copies as $copy)
                                            <div class="panel panel-primary section">
                                                <div class="panel-heading">
                                                    <span class="pull-right">
                                                        {{count($section->questions)}} questions

                                                        @if($section->is_cloneable && $copy->copy_number == 0)
                                                            @if(!is_null($target))
                                                                <form style="display:inline-block" action="{{route('surveys.sections.clone', [$survey->hash, $target->hash, $section->id])}}" method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="section_id" value="{{$section->id}}" />
                                                                    <button type="submit" class="btn btn-xs btn-default">Clone Section</button>
                                                                </form>
                                                            @else
                                                                <button class="btn btn-xs btn-default">Clone Section</button>
                                                            @endif
                                                        @endif
                                                        @if($copy->copy_number != 0)
                                                            <form style="display:inline-block" action="{{route('surveys.sections.remove_clone', [$survey->hash, $target->hash, $copy->id])}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="copy_id" value="{{$copy->id}}" />
                                                                <button type="submit" class="btn btn-xs btn-default">Remove Cloned Section</button>
                                                            </form>
                                                        @endif
                                                    </span>
                                                    {{$section->title}} @if($section->is_cloneable) - {{$copy->copy_number + 1}} @endif
                                                </div>
                                                <div class="panel-body">
                                                    @if(!is_null($section->description) && strlen(trim($section->description)) > 0)
                                                        <div class="alert alert-info">{{$section->description}}</div>
                                                    @endif
                                                    @foreach($copy->questions as $index => $question)
                                                        <div class="question">
                                                            <h4 class="question_header lead">{{$index + 1}}. {{$question->question}}</h4>

                                                            <div class="question_body @if(count($question->children)>0) parent_question @endif">
                                                                @include('survey._question_answer', ['question' => $question, 'copy' => $copy ])
                                                            </div>
                                                            @foreach($question->children as $child_index => $child)
                                                                <div class="child_question" data-id="{{$child->id}}" data-answer="{{json_encode($child->parent_answers)}}">
                                                                    <h4 class="question_header lead">{{chr(ord('A') + $child_index)}}. {{$child->question}}</h4>

                                                                    <div class="question_body">
                                                                        @include('survey._question_answer', ['question' => $child, 'copy' => $copy ])
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        @endforeach
                                    @endforeach

                                    @if(!is_null($target))
                                        <form id="survey_main" action="{{route('survey.submit', [$survey->hash, $target->hash])}}" method="post">
                                        @csrf
                                    @endif
                                    <div class="row">
                                        <div class="col-sm-12" style="text-align: right;">
                                            Thank you for your responses!
                                            <button class="btn btn-primary">Submit Answers</button>
                                        </div>
                                    </div>
                                    @if(!is_null($target))
                                        </form>
                                    @endif
                                </div>
                            </div>

                        @else
                            <div class="alert alert-danger">
                                @if($survey->status == \AssetIQ\Models\Surveys\SurveyStatusOptions::Stopped)
                                    Sorry, this survey has been stopped.
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        (function ($) {

            @if(!is_null($target))
                var answer_url = '{{route('survey.submit.answer', [$survey->hash, $target->hash])}}';
                var file_url = '{{route('survey.submit.file', [$survey->hash, $target->hash])}}';
            @endif

            $(document).ready(function () {

                @if(is_null($target))
                    setTimeout(function(){
                        window.location.reload(1);
                    }, 5000);
                @endif

                $('.parent_question').each(function () {
                    var parent_body = $(this);
                    var wrapper = parent_body.closest('.question');
                    var el = parent_body.find('input, select, textarea');
                    var current_answers = [];
                    switch (el.attr('type')) {
                        case 'checkbox':
                        case 'radio':
                            parent_body.find('input:checked').each(function () {
                                current_answers.push($(this).val());
                            })
                            break;
                        default:
                            current_answers.push(el.val());
                    }

                    update_children(wrapper, current_answers);
                })

                $('.question_body input:not([type="file"]), .question_body select, .question_body textarea').change(function () {
                    var el = $(this);

                    var data = {};
                    data.question_id = el.data('qid');
                    data.copy_id = el.data('cid');
                    data.element_type = el.attr('type');
                    data.element_name = el.attr('name');
                    data.current_answer = el.val();

                    if(data.question_id === undefined){
                        return;
                    }

                    if (data.element_type === 'checkbox') {
                        data.current_answer = [];
                        $('.question_body input[name="' + data.element_name + '"]:checked').each(function () {
                            data.current_answer.push($(this).val());
                        });
                    }

                    var my_body = el.closest('.question_body');
                    if (my_body.hasClass('parent_question')) {
                        update_children(my_body.closest('.question'), data.current_answer);
                    }

                    @if(!is_null($target))
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': Laravel.csrfToken
                            },
                            url    : answer_url,
                            type   : 'POST',
                            data   : data,
                            success: function (response) {
                                el.removeClass('error');
                            },
                            error  : function (response) {
                                if (response.responseJSON && response.responseJSON.errors && response.responseJSON.errors['current_answer']) {
                                    el.addClass('error');
                                    alert(response.responseJSON.errors['current_answer']);
                                } else {
                                    alert('Sorry, something unexpected happen. Please reload this page and try again.');
                                }
                            }
                        })
                    @endif
                });

                $('.question_body input[type="file"]').change(function(){
                    let el = $(this);

                    var data = {};
                    data.question_id = el.data('qid');
                    data.copy_id = el.data('cid');
                    data.element_type = el.attr('type');
                    data.element_name = el.attr('name');
                    data.current_answer = el.val();

                    if(data.question_id === undefined){
                        return;
                    }

                    let form_data = new FormData();
                    form_data.append('file', this.files[0]);
                    form_data.append('question_id', data.question_id);
                    form_data.append('copy_id', data.copy_id);
                    form_data.append('element_type', data.element_type);
                    form_data.append('element_name', data.element_name);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': Laravel.csrfToken
                        },
                        url    : file_url,
                        type   : 'POST',
                        data   : form_data,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            var wrapper = $('#file_q_' + data.question_id + '_c_' + data.copy_id).html('');
                            if(response.is_image){
                                wrapper.append('<img src="' + response.download_inline_url + '" title="' + response.file_name + '" style="width:80%;" /><br />');
                            }
                            wrapper.append('<a href="' + response.download_url + '" target="_blank" style="margin-top:10px;display:inline-block"><i class="glyphicon glyphicon-download"></i> ' + response.file_name + '</a>')
                            el.removeClass('error');
                        },
                        error  : function (response) {
                            if (response.responseJSON && response.responseJSON.errors && response.responseJSON.errors['file']) {
                                el.addClass('error');
                                alert(response.responseJSON.errors['file']);
                            } else {
                                alert('Sorry, something unexpected happen. Please reload this page and try again.');
                            }
                        }
                    })
                })
            });

            function update_children(wrapper, current_answers) {
                if (!Array.isArray(current_answers)) {
                    current_answers = [current_answers];
                }

                wrapper.find('.child_question').each(function () {
                    var child_wrapper = $(this);
                    var parent_answer = child_wrapper.data('answer');

                    var is_disabled = true;
                    if(parent_answer === null){
                        is_disabled = false;
                    } else {
                        for(var j=0;j<current_answers.length;j++){
                            if(parent_answer.includes(current_answers[j])){
                                is_disabled = false;
                            }
                        }
                    }

                    if (!is_disabled) {
                        child_wrapper.find('.question_body input, .question_body select, .question_body textarea').prop('disabled', false);
                    } else {
                        child_wrapper.find('.question_body input[type="text"], .question_body input[type="file"], .question_body input[type="date"], .question_body input[type="url"], .question_body input[type="email"], .question_body select, .question_body textarea')
                            .prop('disabled', true)
                            .val('');
                        child_wrapper.find('.question_body input[type="checkbox"], .question_body input[type="radio"]')
                            .prop('disabled', true)
                            .prop('checked', false);
                    }
                })
            }
        })(jQuery);
    </script>
@endpush