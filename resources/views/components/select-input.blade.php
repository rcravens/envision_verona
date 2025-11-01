@props(['disabled' => false, 'options' => [], 'value' => null])

@php $disabled = $disabled || count($options) == 0; @endphp

<select @disabled($disabled) {{ $attributes->twMerge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
    @php $is_list = array_is_list($options); @endphp
    @foreach($options as $val => $name)
        @php $option_val = $is_list ? $name : $val; @endphp
        <option @if($value==$option_val) selected="selected" @endif value="{{$option_val}}">{{$name}}</option>
    @endforeach
</select>
