<div class="slider-question space-y-3" @if(!is_null($question->parent_id)) data-conditional-parent="{{$question->parent->id}}" data-conditional-value="{{implode(',', $question->parent_values)}}" @endif>
    <label for="{{$question->id}}" class="block text-gray-800 dark:text-gray-100 font-semibold">
        {{$question->question}}
        @if($question->is_required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="flex items-center space-x-3">
        @php
            $min_value = $question->allowed_values[0] ?? 0;
            $max_value = is_null($question->allowed_values) || count($question->allowed_values) < 2 ? 10 : last($question->allowed_values);
            $answers = is_null($answer) ? $question->default_values ?? [] : $answer;
            $current_value = $answers[0] ?? $min_value;
            $has_error = $errors->has($question->id) ?? false;
        @endphp
        <input type="range"
               id="{{$question->id}}"
               name="{{$question->id}}"
               @if($question->is_required) required @endif
               min="{{$min_value}}" max="{{$max_value}}"
               value="{{old($question->id, $current_value)}}" class="slider flex-1"/>
        <span class="slider-value w-8
                        {{ $has_error ? 'border-red-400' : 'border-gray-700 dark:border-gray-200 focus:ring-blue-500' }}
                     font-semibold">
            {{$current_value}}
        </span>
    </div>
    @error($question->id)
    <div class="text-red-600 italic">* {{ $message }}</div>
    @enderror
</div>
