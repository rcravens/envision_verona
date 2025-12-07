<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<x-layouts._head/>
<body class="text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 antialiased !p-0 transition-colors duration-300">

<div class="min-h-[calc(100vh-84px)] ">
    @yield('content', $slot ?? '' )
</div>

<x-layouts._footer/>

<x-layouts._alerts/>

@stack('scripts', '')

@livewireScripts

</body>
</html>
