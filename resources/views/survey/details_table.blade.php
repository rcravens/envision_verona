@extends('layouts.app_fullwidth')

@push('styles')
    <link rel="stylesheet" type="text/css" href="/css/surveys.css">
    <style type="text/css">
        .photo {
            display: block;
            margin: 3px;
            padding: 3px;
            text-align: center;
        }
        .photo img {
            width:150px;
            display: block;
        }
    </style>
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
                                        <option value="{{$code}}">{{$location->code . ' - ' . $location->city . ', ' . $location->state}}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                        <div class="col-sm-12">
                            <table id="my_table" class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        @foreach($header as $head)
                                            <th>{!! $head !!}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $cols)
                                        <?php $code = is_null($audit) ? '' : $cols[0]->html; ?>
                                        <tr class="location {{$code}}">
                                            @foreach($cols as $col)
                                                <td>
                                                    {!! $col->html !!}
                                                    @if(!is_null($audit) && !is_null($col->survey_answer))
                                                        <br />
                                                        @if(count($col->tasks)>0)
                                                            @foreach($col->tasks as $index => $task)
                                                                <?php
                                                                    $label_type = 'label-default';
																	if($task->percent_complete != 100)
                                                                    {
																		if(!is_null($task->due_at) && $task->due_at<\Carbon\Carbon::now())
                                                                        {
																			$label_type = 'label-danger';
                                                                        }
                                                                    }
                                                                    else
                                                                    {
																		$label_type = 'label-success';
                                                                    }
                                                                ?>
                                                                <span class="label {{$label_type}}" data-toggle="tooltip" title="{{$task->description}}">{{$index + 1}}</span>
                                                            @endforeach
                                                        @endif
                                                        <small><button type="button" style="margin:0;padding:0;" data-id="{{$col->survey_answer->id}}" data-type="{{\AssetIQ\Models\Surveys\SurveyAnswer::class}}" data-tasktype="{{\AssetIQ\Models\TaskTypeOptions::Audit}}" class="create_task btn btn-link">add task</button></small>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!is_null($audit))
        @include('tasks.modal', ['owner' => null, 'task_type' => \AssetIQ\ProjectX\Tasks\AuditTask::type()])
    @endif

@endsection

@push('scripts')
    <script type="application/javascript">
        (function ($) {
            $(document).ready(function () {

                $('#location_filter').change(function(){
                    var code = $(this).val();
                    if(code === ''){
                        $('tr.location').show();
                    } else {
                        $('tr.location').hide();
                        $('tr.location.' + code).show();
                    }
                })

                var column_defs = [
                    ];

                $('#my_table').DataTable({
                    dom        : 'Bfrtip',
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
                            customize: function(doc) {
                                if(doc) {
                                    doc.images = doc.images || {};

                                    for (var i=0;i<doc.content[1].table.body.length;i++) {
                                        for(var j=0;j<doc.content[1].table.body[i].length;j++){

                                            var el = $('<div>');
                                            var txt = doc.content[1].table.body[i][j].text;

                                            el.append(txt);

                                            if(txt.startsWith('<span class="photo">') === false){
                                                // Text column
                                                doc.content[1].table.body[i][j].text = el.text();
                                            } else {
                                                // Image column
                                                var content = [];
                                                el.find('img').each(function(){
                                                    var is_image = $(this).data('base64') === 1;

                                                    if(is_image) {
                                                        var img = {
                                                            image: $(this).attr('src'),
                                                            width: 150
                                                        };
                                                        content.push(img);
                                                    }

                                                    var name = {
                                                        text: 'name: ' + $(this).attr('alt')
                                                    };
                                                    content.push(name);

                                                    var ext = {
                                                        text: 'extension: ' + $(this).data('ext')
                                                    }
                                                    content.push(ext);

                                                    var url = {
                                                        text: 'URL: ' + $(this).data('url')
                                                    }
                                                    content.push(url);

                                                    var divider = {
                                                        text: '---------------------'
                                                    }
                                                    content.push(divider);

                                                });
                                                doc.content[1].table.body[i][j] = content;
                                            }
                                        }
                                    }
                                }
                            },
                            exportOptions: {
                                stripHtml: false,
                                columns: 'thead th:not(.no_export)'
                            }
                        }
                    ],
                    paging     : false,
                    fixedHeader: false,
                    scrollY       : '60vh',
                    scrollX       : true,
                    scrollCollapse: true,
                    fixedColumns  : {
                        leftColumns: {{is_null($audit) ? 2 : 5}}
                    },
                    columnDefs : column_defs
                });

            });
        })(jQuery);
    </script>
@endpush
