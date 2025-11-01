@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    <div class="container">
        <div class="row" style="margin-bottom: 30px;">
            <div class="col-sm-12 text-center">
                <a href="{{route('survey_templates.preview', ['template' => $template->id] )}}" target="_blank" class="btn btn-success">Preview Survey</a>
                <a href="{{route('survey_templates.index')}}" class="btn btn-default">&laquo; All Templates</a>
            </div>
        </div>

        <h1>Survey Builder</h1>

        @livewire('surveys.template-editor', ['template' => $template])

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
