@props(['disabled' => false, 'options' => [], 'value' => null])

@php $disabled = $disabled || count($options) == 0; @endphp

<select @disabled($disabled) {{ $attributes->twMerge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}>
    @php $is_list = array_is_list($options); @endphp
    @foreach($options as $val => $name)
        @php $option_val = $is_list ? $name : $val; @endphp
        <option @if($value==$option_val) selected="selected" @endif value="{{$option_val}}">{{$name}}</option>
    @endforeach
</select>
