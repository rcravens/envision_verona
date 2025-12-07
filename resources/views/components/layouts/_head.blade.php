<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark light">
    <title>@yield('page_title', 'Envision Verona')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles', '')

    @livewireStyles
</head>
