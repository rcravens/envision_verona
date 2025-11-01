<x-empty-layout :hide_footer="false">
    @include('layouts._topnav', ['hide_menu' => true])

    <!-- Content Header -->
    @isset($header)
        <header class="">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <div class="sm:justify-center @if(isset($header)) pt-4 @else pt-12 @endif sm:pt-0">
        <div class="mx-auto w-full sm:max-w-md @if(isset($header)) mt-4 @else mt-12 @endif px-6 py-4 text-gray-900 dark:text-white  bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>

    <!-- Context Footer -->
    @isset($footer)
        <footer class="">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $footer }}
            </div>
        </footer>
    @endisset

</x-empty-layout>
