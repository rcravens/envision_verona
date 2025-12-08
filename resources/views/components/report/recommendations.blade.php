@props(['title' => 'Recommendation Based on the Report'])

<div class="p-4 bg-white dark:bg-gray-900 rounded-lg">
    <h4 class="text-xl font-bold mb-3">{{$title}}</h4>
    <div class="ml-6 flex flex-col gap-4">
        {{$slot}}
    </div>
</div>

