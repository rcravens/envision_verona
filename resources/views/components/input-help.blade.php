@props(['value'])

<span {{ $attributes->twMerge(['class' => 'float-end font-medium text-xs text-gray-700 dark:text-gray-400']) }}>
    {{ $value ?? $slot }}
</span>
