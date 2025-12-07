<x-layouts.empty :hide_footer="false">

    <x-layouts._nav :hide_menu="true"/>

    <main class="mt-8">
        <div class="sm:justify-center @if(isset($header)) pt-4 @else pt-12 @endif sm:pt-0">
            <div class="mx-auto w-full sm:max-w-md @if(isset($header)) mt-4 @else mt-12 @endif px-6 py-4 text-gray-900 dark:text-white  bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </main>

</x-layouts.empty>
