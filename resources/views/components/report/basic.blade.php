@props(['report'])

<section {{ $attributes->merge(['class' => 'min-h-screen px-4 py-8 bg-gray-100 dark:bg-gray-800'])}}>
    <div class="max-w-7xl mx-auto">
        <div class="mb-12 flex flex-row items-center justify-between">
            <h1 class="text-4xl font-bold">{{$report->title}} ({{$report->year}}) <a href="{{$report->url}}" class="text-base text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" target="_blank">view</a></h1>
            <h2 class="text-2xl">Report Analysis</h2>
        </div>

        @if(isset($recommendations))
            <div class="flex flex-col gap-8 mb-12">
                {{$recommendations}}

                <hr/>
            </div>
        @endif

        @if(isset($summary))
            <div class="flex flex-col gap-8 mb-12">
                {{$summary}}

                <hr/>
            </div>
        @endif

        <div>
            <h4 class="text-xl font-bold mb-3">Report Notes</h4>
            <div class="ml-6 flex flex-col gap-4">
                @if($slot->isEmpty())
                    No notes found
                @else
                    {{$slot}}
                @endif
            </div>
        </div>

    </div>
</section>
