<!-- https://flowbite.com/docs/components/navbar/ -->
@props([])

@php
    $unique_id = 'dropdown_'.uniqid();
@endphp
<li>
    <button data-dropdown-toggle="{{$unique_id}}" class="flex items-center justify-between w-full py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-white md:p-0 md:w-auto dark:text-white md:dark:hover:text-white dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
        {{ $trigger }}
        <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
    </button>

    <!-- Dropdown menu -->
    <ul id="{{$unique_id}}" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownLargeButton">
            {{ $content }}
        </ul>
    </ul>
</li>
