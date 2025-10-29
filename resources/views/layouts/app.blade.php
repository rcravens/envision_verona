<x-empty-layout :hide_footer="false">
    @include('layouts._topnav')

    <!-- Page Heading -->
    @isset($header)
        <header class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 mt-8 text-blue-500 ">
            {{ $header }}
        </header>
    @endisset

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</x-empty-layout>
