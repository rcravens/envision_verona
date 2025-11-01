<nav class="bg-white dark:bg-gray-900 w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{url('/')}}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="text-3xl">Envision Verona</span>
        </a>
        <button data-collapse-toggle="app-top-nav" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>
        <div class="items-center justify-between w-auto md:flex md:w-auto md:order-1" id="app-top-nav">
            <ul class="flex flex-col items-center p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                @if(!isset($hide_menu))
                    <x-nav-link :href="route('home.dashboard')" :active="request()->routeIs('home.dashboard')">{{__('Home')}}</x-nav-link>
                    <x-nav-link :href="route('home.dashboard')" :active="request()->routeIs('home.xxx')">{{__('Population')}}</x-nav-link>
                    <x-nav-link :href="route('home.dashboard')" :active="request()->routeIs('home.xxx')">{{__('Housing')}}</x-nav-link>
                    <x-nav-link :href="route('home.dashboard')" :active="request()->routeIs('home.xxx')">{{__('Projects')}}</x-nav-link>
                    <x-nav-link :href="route('home.dashboard')" :active="request()->routeIs('home.xxx')">{{__('About')}}</x-nav-link>

                    @auth

                    @endauth
                @endif
            </ul>
            @guest
                @if(!request()->routeIs('login') && Route::has('login'))
                    <div class="p-0 mt-4 mx-2 md:mt-0">
                        <a href="{{route('login')}}" class="md:ml-8 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">{{__('Login')}}</a>
                        @if (Route::has('register'))
                            <a href="{{route('register')}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{__('Start Free Trial')}}</a>
                        @endif
                    </div>
                @endif
            @endguest
            <button id="theme-toggle" type="button" class=" text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg m-0 text-sm p-2.5">
                <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                        fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
</nav>

@push('scripts')
    <script>
        // DARK MODE TOGGLE BUTTON
        const themeToggleDarkIcon = document.getElementById("theme-toggle-dark-icon");
        const themeToggleLightIcon = document.getElementById("theme-toggle-light-icon");

        if (
            localStorage.getItem("color-theme") === "dark" ||
            (!("color-theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)
        ) {
            document.documentElement.classList.add("dark");
            if (themeToggleLightIcon) {
                themeToggleLightIcon.classList.remove("hidden");
            }
        } else {
            document.documentElement.classList.remove("dark");
            if (themeToggleDarkIcon) {
                themeToggleDarkIcon.classList.remove("hidden");
            }
        }

        const themeToggleBtn = document.getElementById("theme-toggle");

        themeToggleBtn.addEventListener("click", function () {
            themeToggleDarkIcon.classList.toggle("hidden");
            themeToggleLightIcon.classList.toggle("hidden");

            if (localStorage.getItem("color-theme")) {
                if (localStorage.getItem("color-theme") === "light") {
                    document.documentElement.classList.add("dark");
                    localStorage.setItem("color-theme", "dark");
                } else {
                    document.documentElement.classList.remove("dark");
                    localStorage.setItem("color-theme", "light");
                }
            } else {
                if (document.documentElement.classList.contains("dark")) {
                    document.documentElement.classList.remove("dark");
                    localStorage.setItem("color-theme", "light");
                } else {
                    document.documentElement.classList.add("dark");
                    localStorage.setItem("color-theme", "dark");
                }
            }
        });
    </script>
@endpush

