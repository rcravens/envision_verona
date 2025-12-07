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
    @foreach($question->allowed_values ?? [] as $allowed_value)
        <label class="flex items-center space-x-3 p-3 border
                {{$has_error ? 'border-red-400' : 'border-gray-200 dark:border-gray-700'}}
                rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
            <input type="radio"
                   id="{{$question->id . '-' . $allowed_value}}"
                   name="{{$question->id}}"
                   @if($question->is_required) required @endif
                   @if(in_array(old($question->id, $allowed_value), $answers)) checked @endif
                   value="{{$allowed_value}}"
                   class="text-blue-600 focus:ring-blue-500"/>
            <span class="text-gray-700 dark:text-gray-200">{{$allowed_value}}</span>
        </label>
    @endforeach
    @error($question->id)
    <div class="text-red-600 italic">* {{ $message }}</div>
    @enderror
</div>
