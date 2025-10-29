@props(['disabled' => false, 'value' => $value])

@php
    if(is_array($value))
    {
        $value = implode(PHP_EOL, $value);
    }
@endphp

<textarea @disabled($disabled) {{ $attributes->twMerge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}>{{$value}}</textarea>
