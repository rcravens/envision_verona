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
                            <a href="{{route('audits.show', [$audit->id])}}" class="btn btn-default btn-xs">&laquo; Audit Dashboard</a>
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
                            <span class="pull-right">
                                <a href="{{route('surveys.answers.download', [$survey->id])}}" class="btn btn-link"><i class="glyphicon glyphicon-download"></i> Download Summary</a>
                            </span>
                            <a href="{{route('surveys.show', [$survey->id])}}" class="btn btn-link"><i class="glyphicon glyphicon-th-large"></i> Summary</a>
                            <a href="{{route('surveys.details', [$survey->id])}}" class="btn btn-link"><i class="glyphicon glyphicon-th"></i> Detail</a>
                            <a href="{{route('surveys.answers.table', [$survey->id])}}" class="btn btn-link"><i class="glyphicon glyphicon-th-list"></i> List</a>
                        </div>
                    </div>

                    <div class="row">
                        @include('survey._survey_responses', ['survey' => $survey])
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

            });
        })(jQuery);
    </script>
@endpush
