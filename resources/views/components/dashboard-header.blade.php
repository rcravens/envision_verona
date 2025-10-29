@props(['title', 'subtitle' => null])

<div {{ $attributes->class(['flex flex-row items-end justify-items-start mb-4 gap-4 mx-4 sm:mx-0']) }}>
    <div class="flex items-center gap-4">
        <div style="padding-top:9px;">
            <x-svgs.diamond class="w-8 h-8 text-white"/>
        </div>
        <h1 class="font-antonio text-blue-500 font-extrabold uppercase text-2xl sm:text-3xl md:text-4xl lg:text-5xl leading-tight">
            {{ __($title) }}
        </h1>
    </div>
    @if(!is_null($subtitle))
        <div class="font-antonio text-xl">
            {{ __($subtitle) }}
        </div>
    @endif
</div>
