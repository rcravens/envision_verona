@props(['section'])
<section {{ $attributes->merge(['class' => 'survey-section space-y-6 p-6 bg-gray-100 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 transition-all duration-300']) }} >
    <header class="mb-4">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
            {{$section->title}}
        </h3>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
            {{$section->subtitle}}
        </p>
    </header>

    {{$slot}}
</section>
