@props(['title' => 'Report Summary'])

<div>
    <h4 class="text-xl font-bold mb-3">{{$title}}</h4>
    <div class="ml-6 flex flex-col gap-4">
        {{$slot}}
    </div>
</div>


