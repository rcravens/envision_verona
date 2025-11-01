<div class="questions col-sm-8 col-sm-offset-2">
    @foreach($survey->sections as $section)
        @if($section->clone_number == 0)
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
                        <div class="question">
                            <h4 class="question_header lead">{{$index + 1}}. {{$question->question}}</h4>

                            <div class="question_summary @if(count($question->children)>0) parent_question @endif">
                                <table class="table table-condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>Response</th>
                                            <th style="width:75px;">Count</th>
                                            <th style="width:75px;">Percent</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($question->answer_summary as $response => $obj)
                                            <tr>
                                                <td>{{$response}}</td>
                                                <td>{{$obj->count}}</td>
                                                <td>{{number_format($obj->percent, 1)}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @foreach($question->children as $child_index => $child)
                                @if(!is_null($child->parent_answers))
                                    <div class="child_question">
                                        <h4 class="question_header lead">
                                            {{chr(ord('A') + $child_index)}}. {{$child->question}}
                                            <small>({{implode(', ', $child->parent_answers)}})</small>
                                        </h4>

                                        <div class="question_summary">
                                            <table class="table table-condensed table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Response</th>
                                                    <th style="width:75px;">Count</th>
                                                    <th style="width:75px;">Percent</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($child->answer_summary as $response => $obj)
                                                    <tr>
                                                        <td>{{$response}}</td>
                                                        <td>{{$obj->count}}</td>
                                                        <td>{{number_format($obj->percent, 1)}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach

                </div>
            </div>
        @endif
    @endforeach
</div>