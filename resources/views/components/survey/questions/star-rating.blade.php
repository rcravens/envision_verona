<div class="star-rating space-y-3" @if(!is_null($question->parent_id)) data-conditional-parent="{{$question->parent->id}}" data-conditional-value="{{implode(',', $question->parent_values)}}" @endif>
    <label for="{{$question->id . '-star1'}}" class="block text-gray-800 dark:text-gray-100 font-semibold">
        {{$question->question}}
        @if($question->is_required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="stars flex space-x-1 cursor-pointer">
        @php
            $answers = is_null($answer) ? $question->default_values ?? [] : $answer;
            $current_answer = $answers[0] ?? 0;
            $has_error = $errors->has($question->id) ?? false;
        @endphp
        @for($i=1;$i<=5;$i++)
            <label>
                <input type="radio"
                       id="{{$question->id . '-star' . $i}}"
                       name="{{$question->id}}"
                       value="{{$i}}"
                       @if($i==1 && $question->is_required) required @endif
                       class="hidden"/>
                <span class="star @if($i<=old($question->id, $current_answer)) text-yellow-400 @else {{$has_error ? 'text-red-500':'text-gray-400' }} @endif text-2xl">★</span>
            </label>
        @endfor
    </div>
    @error($question->id)
    <div class="text-red-600 italic">* {{ $message }}</div>
    @enderror
</div>
