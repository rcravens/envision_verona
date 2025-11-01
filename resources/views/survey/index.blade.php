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
                        <a href="{{route('survey_templates.index')}}" class="btn btn-default btn-xs">&laquo; All Templates</a>
                    </span>
                    Surveys
                </div>

                <div class="panel-body">

                    @if(count($surveys) > 0)
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="my_table" class="table table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            @if ( Auth::user()->can_be_any( [ \AssetIQ\Models\SecurityRoleOptions::SurveyAdmin ] ) )
                                                <th style="width:75px;"></th>
                                            @endif
                                            <th style="width:120px;">ID</th>
                                            <th>Title</th>
                                            <th>Started By</th>
                                            <th>Status</th>
                                            <th>Questions</th>
                                            <th>Invited</th>
                                            <th>Completion %</th>
                                            @if ( Auth::user()->can_be_any( [ \AssetIQ\Models\SecurityRoleOptions::SurveyAdmin ] ) )
                                                <th></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($surveys as $survey)
                                            <?php $audit = array_key_exists($survey->id, $audit_lut) ? $audit_lut[$survey->id] : null; ?>
                                            <tr>
                                                @if ( Auth::user()->can_be_any( [ \AssetIQ\Models\SecurityRoleOptions::SurveyAdmin ] ) )
                                                    <td>
                                                        @if(is_null($audit))
                                                            <a href="{{route('surveys.invite', [$survey->id])}}" title="Invite users" data-toggle="tooltip" data-placement="right"><i class="fa fa-users"></i></a>
                                                        @else
                                                            <a href="{{route('audits.invite', [$audit->id])}}" title="Invite users" data-toggle="tooltip" data-placement="right"><i class="fa fa-users"></i></a>
                                                        @endif
                                                        <a href="{{route('surveys.show', [$survey->id])}}" title="Dashboard" data-toggle="tooltip" data-placement="right"><i class="glyphicon glyphicon-stats"></i></a>
                                                        @if($survey->status == \AssetIQ\Models\Surveys\SurveyStatusOptions::Started)
                                                            <form action="{{route('surveys.stop', [$survey->id])}}" method="post" style="display: inline-block">
                                                                {{csrf_field()}}
                                                                <button type="submit" class="btn btn-link" style="padding:0" title="Stop the survey" data-toggle="tooltip" data-placement="right"><i class="glyphicon glyphicon-stop"></i></button>
                                                            </form>
                                                        @else
                                                            <form action="{{route('surveys.start', [$survey->id])}}" method="post" style="display: inline-block">
                                                                {{csrf_field()}}
                                                                <button type="submit" class="btn btn-link" style="padding:0" title="Resume the survey" data-toggle="tooltip" data-placement="right"><i class="glyphicon glyphicon-play"></i></button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                @endif
                                                <td>
                                                    SVY-{{$survey->id}}
                                                    @if(!is_null($audit))
                                                        / <a href="{{route('audits.show', [$audit->id])}}" target="_blank">AUD-{{$audit->id}}</a>
                                                    @endif
                                                </td>
                                                <td>{{$survey->title}}</td>
                                                <td>{{$survey->author->first_name}} {{$survey->author->last_name}}</td>
                                                <td>{{$survey->status}}</td>
                                                <td>{{$survey->question_count}}</td>
                                                <td>{{$survey->invited_count}}</td>
                                                <td>{{ $survey->invited_count==0 ? '--' : number_format(100*$survey->completed_count/$survey->invited_count, 1)}}</td>
                                                @if ( Auth::user()->can_be_any( [ \AssetIQ\Models\SecurityRoleOptions::SurveyAdmin ] ) )
                                                    <td>
                                                        <form action="{{route('surveys.destroy', [$survey->id])}}" class="confirm" method="post">
                                                            @method('DELETE')
                                                            {{csrf_field()}}
                                                            <button type="submit" class="btn btn-link"><i class="text-danger fa fa-trash"></i></button>
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
                                <em>No surveys found</em>
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