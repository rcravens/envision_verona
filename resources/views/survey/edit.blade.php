@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    <div class="container">
        <div class="row" style="margin-bottom: 30px;">
            <div class="col-sm-12 text-center">
                <a href="{{route('survey.preview', [$survey->hash])}}" target="_blank" class="btn btn-success">Preview Survey</a>
                <a href="{{route('surveys.index')}}" class="btn btn-default">&laquo; All Surveys</a>
            </div>
        </div>

        <h1>Survey Builder</h1>
        @if($survey->status == \AssetIQ\Models\Surveys\SurveyStatusOptions::Pending)
            @livewire('surveys.survey-editor', ['survey' => $survey])
        @else
            <p class="alert alert-danger">Once a survey is started, it can no longer be edited.</p>
        @endif

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
