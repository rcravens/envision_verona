<x-layouts.empty :hide_footer="false">
    <x-layouts._nav/>

    <main>
        @yield('content', $slot ?? '')
    </main>

</x-layouts.empty>
