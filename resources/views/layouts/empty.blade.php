<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts._head')
<body class="text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900 antialiased !p-0">
<div class="min-h-[calc(100vh-84px)] ">
    {{ $slot }}
</div>
@include('layouts._footer')
@include('layouts._alert')

@include('layouts._scripts')
@stack('scripts', '')

@livewireScripts

</body>
</html>
