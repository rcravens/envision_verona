<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page_title', 'Envision Verona')</title>
    <meta name="description" content="@yield('page_description', 'Invest in your future with the best technology screencasts on the web!')">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire styles -->
    @livewireStyles
</head>
