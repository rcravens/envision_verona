@props(['value'])

<span {{ $attributes->twMerge(['class' => 'float-end font-medium text-xs text-gray-700']) }}>
    {{ $value ?? $slot }}
</span>
