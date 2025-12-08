@php use Illuminate\Support\Facades\Route; @endphp
<nav class="block bg-white dark:bg-gray-900 w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{url('/')}}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <span class="text-3xl">Envision Verona</span>
        </a>
        <button data-collapse-toggle="app-top-nav" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"></path>
            </svg>
        </button>
        <div class="flex-col md:flex-row items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="app-top-nav">
            @if(!isset($hide_menu))
                <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 p-4 md:p-0 mt-4 md:mt-0 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-4 rtl:space-x-reverse md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <x-nav.link :href="route('home')" active="{{ request()->routeIs('home') }}">Home</x-nav.link>
                    <x-nav.link :href="route('chat')" active="{{ request()->routeIs('chat') }}">Chat</x-nav.link>
                    <x-nav.dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <span>Projects</span>
                                <span class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-nav.dropdown-link :href="route('projects.index')">Project List</x-nav.dropdown-link>
                            @foreach(projects()->all() as $project)
                                <x-nav.dropdown-link :href="route('projects.analysis', $project->slug)">{{$project->name}}</x-nav.dropdown-link>
                            @endforeach
                        </x-slot>
                    </x-nav.dropdown>
                    <x-nav.dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <span>Reports</span>
                                <span class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-nav.dropdown-link :href="route('reports.index')">Report List</x-nav.dropdown-link>
                            @php $reports = \App\Models\Report::orderBy('slug')->get(); @endphp
                            @foreach($reports as $report)
                                <x-nav.dropdown-link href="{{route('reports.analysis', $report->slug)}}">{{$report->title}}</x-nav.dropdown-link>
                            @endforeach
                        </x-slot>
                    </x-nav.dropdown>
                    <x-nav.dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <span>Models</span>
                                <span class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-nav.dropdown-link :href="route('models.population')">Population Growth</x-nav.dropdown-link>
                        </x-slot>
                    </x-nav.dropdown>
                </div>
                <div class="flex flex-col md:flex-row items-center md:space-x-2 space-y-2 md:space-y-0 p-0 mt-4 ml-0 md:ml-6 mx-2 md:mt-0">
                    @auth
                        <div class="">
                            <x-nav.dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <span>{{ Auth::user()->name }}</span>
                                        <span class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-nav.dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-nav.dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-nav.dropdown-link :href="route('logout')"
                                                             onclick="event.preventDefault();
                                                                 this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-nav.dropdown-link>
                                    </form>
                                </x-slot>
                            </x-nav.dropdown>
                        </div>
                    @endauth
                    @guest
                        @if(!request()->routeIs('login') && Route::has('login'))
                            <x-nav.link-button-secondary href="{{route('login')}}" active="{{ request()->routeIs('login') }}">Login</x-nav.link-button-secondary>
                            <x-nav.link-button-primary href="{{route('register')}}" active="{{ request()->routeIs('register') }}">Register</x-nav.link-button-primary>
                        @endif
                    @endguest
                </div>
            @endif
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
    <script>
        const menuToggleBtn = document.querySelector('[data-collapse-toggle="app-top-nav"]');
        const menuTarget = document.getElementById('app-top-nav');

        if (menuToggleBtn && menuTarget) {
            menuToggleBtn.addEventListener('click', function () {
                const isExpanded = menuTarget.classList.toggle('hidden');
                menuToggleBtn.setAttribute('aria-expanded', !isExpanded);
            });
        }
    </script>
@endpush
