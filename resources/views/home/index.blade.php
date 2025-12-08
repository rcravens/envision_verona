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

    <section class="w-full py-0 md:py-8 max-w-6xl mx-auto flex flex-col md:flex-row gap-4 justify-center items-top px-6 bg-gray-100 dark:bg-gray-900">
        <div class="p-4 bg-white dark:bg-gray-900 rounded-lg">
            <h4 class="text-4xl font-bold mb-3">City of Verona Recommendations</h4>
            <div class="text-lg mb-6 italic">
                These recommendations were developed by synthesizing findings from multiple independent reports, each addressing different aspects of housing, population trends, zoning practices, and long-range planning for Verona and the surrounding Dane County region.
                For each report, the original source document was reviewed in detail, and a summary was created along with key recommendations that were extracted as part of this analyses.
                The consolidated recommendations presented here represent the aggregate insights of that full body of work.
                Individual report analyses, including summaries and links to the original documents, are available in the main menu for reference.
            </div>
            <div class="flex flex-col gap-12">
                <div class="ml-6 flex flex-col gap-4">
                    <div class=" text-2xl text-yellow-500">A. Near-Term (5-10 years): Verona should target a housing mix that is 60-70% ownership and 30-40% rental for new housing production.</div>
                    <div class="ml-8 flex flex-col gap-4">
                        <x-report.ul-with-header title="Owner housing is the true bottleneck:">
                            <li>Verona’s owner (for-sale) vacancy is effectively 0%, which means almost no supply is available at any given time.</li>
                            <li>This produces “down-market competition,” where higher-income households buy homes at lower price points, pushing everyone else downward in the market.</li>
                        </x-report.ul-with-header>
                        <x-report.ul-with-header title="Rental vacancy has normalized:">
                            <li>Rental vacancy in Verona is reported around 7–8%, a healthy level.</li>
                            <li>This means the rental market does not show a short-term shortage as severe as the for-sale market.</li>
                        </x-report.ul-with-header>
                        <x-report.ul-with-header title="Boosting ownership housing would:">
                            <li>Free up rental units for renters</li>
                            <li>Reduce competition in lower-cost segments</li>
                            <li>Improve affordability across the full housing ladder</li>
                            <li>Support population growth without overheating rents</li>
                        </x-report.ul-with-header>
                        <x-report.ul-with-header title="Verona needs ~190 units per year to keep up with growth:">
                            <li>Composition of those units matters</li>
                            <li>Since the rental market is not severely constrained but the ownership market is, <span class="font-bold italic">ownership should be the focus of near-term unit production.</span></li>
                        </x-report.ul-with-header>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="w-full py-0 md:py-8 max-w-6xl mx-auto flex flex-col md:flex-row gap-4 justify-center items-top p-6 bg-gray-100 dark:bg-gray-900">
        <div class="w-full bg-white dark:bg-gray-800 rounded-lg p-4">
            <h2 class="text-center text-xl font-bold mb-4">Projects</h2>
            <ul class="list-disc ml-5 space-y-2 sm:space-y-2">
                @foreach(projects()->all() as $project)
                    <li><a class="hover:underline" href="{{route('projects.analysis', $project->slug)}}">{{$project->name}}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="w-full bg-white dark:bg-gray-800 rounded-lg p-4">
            <h2 class="text-center text-xl font-bold mb-4">Reports</h2>
            <ul class="list-disc ml-5 space-y-2 sm:space-y-2">
                @foreach(reports()->all() as $report)
                    <li><a class="hover:underline" href="{{route('reports.analysis', $report->slug)}}">{{$report->name}}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="w-full bg-white dark:bg-gray-800 rounded-lg p-4">
            <h2 class="text-center text-xl font-bold mb-4">Models</h2>
            <ul class="list-disc ml-5 space-y-2 sm:space-y-2">
                <li><a class="hover:underline" href="{{route('models.population')}}">Population</a></li>
            </ul>
        </div>
    </section>

</x-layouts.app>
