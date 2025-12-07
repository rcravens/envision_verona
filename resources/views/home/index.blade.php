<x-layouts.app>

    <!-- Section 1: Hero -->
    <section id="hero"
             class="h-160 flex flex-col justify-end items-center text-center bg-cover bg-center relative"
             style="background-image: url('/imgs/verona/hometown_usa.jpg');">
        <div class="relative z-10 px-6 py-4 mb-10 rounded-lg backdrop-blur-sm bg-white/70 dark:bg-black/60 ">
            <h1 class="text-6xl md:text-8xl font-extrabold mb-4 tracking-wide font-heading">
                Envision Verona
            </h1>
            <p class="text-lg md:text-xl text-gray-800 dark:text-gray-300 font-body">
                Know Your City. Plan Its Future.
            </p>
        </div>
    </section>

    <section class="w-full max-w-6xl mx-auto flex flex-col md:flex-row gap-4 justify-center items-top px-6 bg-white dark:bg-gray-900 translate-y-10">
        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
            <h2 class="text-center text-xl font-bold mb-4">Projects</h2>
            <ul class="list-disc ml-5 space-y-2 sm:space-y-2">
                @foreach(projects()->all() as $project)
                    <li><a class="hover:underline" href="{{route('projects.analysis', $project->slug)}}">{{$project->name}}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
            <h2 class="text-center text-xl font-bold mb-4">Reports</h2>
            <ul class="list-disc ml-5 space-y-2 sm:space-y-2">
                @foreach(reports()->all() as $report)
                    <li><a class="hover:underline" href="{{route('reports.analysis', $report->slug)}}">{{$report->name}}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-lg p-4">
            <h2 class="text-center text-xl font-bold mb-4">Models</h2>
            <ul class="list-disc ml-5 space-y-2 sm:space-y-2">
                <li><a class="hover:underline" href="{{route('models.population')}}">Population</a></li>
            </ul>
        </div>
    </section>

</x-layouts.app>
