@props(['disabled' => false, 'value' => $value])

@php
    if(is_array($value))
    {
        $value = implode(PHP_EOL, $value);
    }
@endphp

<textarea @disabled($disabled) {{ $attributes->twMerge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>{{$value}}</textarea>
