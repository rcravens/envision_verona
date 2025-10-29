@props(['status'])

@if ($status)
    <div {{ $attributes->twMerge(['class' => 'font-medium text-sm text-green-600 dark:text-green-400']) }}>
        {{ $status }}
    </div>
@endif
