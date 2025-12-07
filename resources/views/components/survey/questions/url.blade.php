<div class="space-y-3" @if(!is_null($question->parent_id)) data-conditional-parent="{{$question->parent->id}}" data-conditional-value="{{implode(',', $question->parent_values)}}" @endif>
    <label for="{{$question->id}}" class="block text-gray-800 dark:text-gray-100 font-semibold">
        {{$question->question}}
        @if($question->is_required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    @php
        $answers = is_null($answer) ? $question->default_values ?? [] : $answer;
        $has_error = $errors->has($question->id) ?? false;
    @endphp
    <input type="url"
           id="{{$question->id}}"
           name="{{$question->id}}"
           @if($question->is_required) required @endif
           @if($question->placeholder) placeholder="{{$question->placeholder}}" @endif
           value="{{old($question->id, $answers[0] ?? null)}}"
           class="w-full p-3 border
                 {{ $has_error ? 'border-red-400 focus:ring-red-500' : 'border-gray-200 dark:border-gray-700 focus:ring-blue-500' }}
                 rounded-xl bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 transition"/>
    @error($question->id)
    <div class="text-red-600 italic">* {{ $message }}</div>
    @enderror
</div>
