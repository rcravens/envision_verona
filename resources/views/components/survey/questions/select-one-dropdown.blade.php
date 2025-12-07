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
    <select id="{{$question->id}}"
            name="{{$question->id}}"
            @if($question->is_required) required @endif
            class="w-full p-3 border
            {{$has_error ? 'border-red-400 focus:ring-red-500' : 'border-gray-200 dark:border-gray-700 focus:ring-blue-500' }}
            rounded-xl bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 transition">
        <option value="" disabled selected>Select an option</option>
        @foreach($question->allowed_values ?? [] as $allowed_value)
            <option @if(in_array(old($question->id, $allowed_value), $answers)) selected @endif value="{{$allowed_value}}">{{$allowed_value}}</option>
        @endforeach
    </select>
    @error($question->id)
    <div class="text-red-600 italic">* {{ $message }}</div>
    @enderror
</div>
