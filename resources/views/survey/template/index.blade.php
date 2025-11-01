@extends('layouts.app')

@push('styles')
    <style type="text/css">
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <span class="pull-right">
                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#new_survey_template_modal">Create Template</button>
                        <a href="{{route('audit_templates.index')}}" class="btn btn-default btn-xs">&laquo; Audit Templates</a>
                        <a href="{{route('surveys.index')}}" class="btn btn-default btn-xs">Surveys &raquo;</a>
                    </span>
                    Survey Templates
                </div>

                <div class="panel-body">

                    @if(count($templates) > 0)
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="my_table" class="table table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            @if ( Auth::user()->can_be_any( [ \AssetIQ\Models\SecurityRoleOptions::SurveyAdmin ] ) )
                                                <th style="width:50px;"></th>
                                            @endif
                                            <th>ID</th>
                                            <th>Department</th>
                                            <th style="width:25%;">Title</th>
                                            <th>Author</th>
                                            <th>Sections</th>
                                            <th>Questions</th>
                                            <th>Surveys</th>
                                            @if ( Auth::user()->can_be_any( [ \AssetIQ\Models\SecurityRoleOptions::SurveyAdmin ] ) )
                                                <th></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($templates as $template)
                                            <tr>
                                                @if ( Auth::user()->can_be_any( [ \AssetIQ\Models\SecurityRoleOptions::SurveyAdmin ] ) )
                                                    <td>
                                                        <form action="{{route('survey_templates.clone', [$template->id])}}" method="post" style="display: inline;">
                                                            {{csrf_field()}}
                                                            <button type="submit" class="btn btn-link" style="padding:0;" title="Clone template" data-toggle="tooltip" data-placement="right"><i class="glyphicon glyphicon-duplicate"></i></button>
                                                        </form>
                                                        <a href="{{route('survey_templates.edit', [$template->id])}}" title="Edit template" data-toggle="tooltip" data-placement="right"><i class="glyphicon glyphicon-wrench"></i></a>
                                                        <form action="{{route('survey_templates.start', [$template->id])}}" method="post" style="display: inline;">
                                                            {{csrf_field()}}
                                                            <button type="submit" class="btn btn-link" style="padding:0;" title="Start a survey" data-toggle="tooltip" data-placement="right"><i class="glyphicon glyphicon-play"></i></button>
                                                        </form>
                                                    </td>
                                                @endif
                                                <td>SVY-TMP-{{$template->id}}</td>
                                                <td>{{is_null($template->department) ? '--' : $template->department}}</td>
                                                <td>{{$template->title}}</td>
                                                <td>{{$template->author->first_name}} {{$template->author->last_name}}</td>
                                                <td>{{count($template->sections)}}</td>
                                                <td>{{$template->total_questions}}</td>
                                                <td>{{$template->total_surveys}}</td>
                                                @if ( Auth::user()->can_be_any( [ \AssetIQ\Models\SecurityRoleOptions::SurveyAdmin ] ) )
                                                    <td>
                                                        <form action="{{route('survey_templates.destroy', [$template->id])}}" class="confirm" method="post">
                                                            @method('DELETE')
                                                            {{csrf_field()}}
                                                            <button type="submit" class="btn btn-link" title="Delete this template" data-toggle="tooltip" data-placement="left"><i class="text-danger fa fa-trash"></i></button>
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sm-12">
                                <em>No templates found</em>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="new_survey_template_modal" tabindex="-1" role="dialog" aria-labeledby="#new_survey_template_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Create Survey</h4>
                </div>
                <form class="form-horizontal" role="form" method="POST" action="{{route('survey_templates.store')}}">

                    {{ csrf_field() }}

                    <div class="modal-body">

                        <div class="form-group">
                            <label for="title" class="col-sm-4 control-label">Title</label>
                            <div class="col-sm-6">
                                <input id="title" name="title" class="form-control" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-sm-4 control-label">Description</label>
                            <div class="col-sm-6">
                                <textarea id="description" name="description" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="department" class="col-sm-4 control-label">Department</label>
                            <div class="col-sm-6">
                                <select id="department" name="department" class="form-control" required>
                                    <option value="">-- Select Department --</option>
                                    @foreach(\AssetIQ\Models\DepartmentOptions::all() as $department)
                                        <option value="{{$department}}">{{$department}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        (function ($) {

            $(document).ready(function () {
                $('#my_table').DataTable({
                    dom        : 'Bfrtip',
                    buttons    : ['copy', 'csv', 'excel', 'pdf', 'print'],
                    paging     : false,
                    fixedHeader: true,
                    columnDefs : [
                    ]
                });

            });
        })(jQuery);
    </script>
@endpush