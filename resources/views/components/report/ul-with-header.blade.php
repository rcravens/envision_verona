@props(['title' => null])

<div class="">
    @if($title)
        <div class="font-bold underline mb-2">{{$title}}</div>
    @endif
    <ul class="list-disc list-inside space-y-2">
        {{ $slot }}
    </ul>
</div>

