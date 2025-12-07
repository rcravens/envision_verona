<x-layouts.app>
    @section('page_description', 'Learn technology and best practices from seasoned developers, architects, and designers.')
    @section('page_type', 'article')

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Scroll-triggered animations
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('opacity-100', 'translate-y-0');
                            entry.target.classList.remove('opacity-0', 'translate-y-10');
                        }
                    });
                }, {threshold: 0.2});

                document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));

                // Keyboard navigation
                const slidesContainer = document.querySelector('.slides'); // new scroll container
                const sections = Array.from(slidesContainer.querySelectorAll('section'));
                let isScrolling = false;

                document.addEventListener('keydown', (e) => {
                    if (!['ArrowDown', 'ArrowRight', 'ArrowUp', 'ArrowLeft'].includes(e.key)) return;
                    e.preventDefault();
                    if (isScrolling) return;
                    isScrolling = true;

                    // Find section whose top is closest to container top
                    let closestIndex = 0;
                    let minDistance = Infinity;
                    sections.forEach((sec, idx) => {
                        const distance = Math.abs(sec.offsetTop - slidesContainer.scrollTop);
                        if (distance < minDistance) {
                            minDistance = distance;
                            closestIndex = idx;
                        }
                    });

                    // Determine next section
                    let targetIndex = closestIndex;
                    if ((e.key === 'ArrowDown' || e.key === 'ArrowRight') && closestIndex < sections.length - 1) {
                        targetIndex = closestIndex + 1;
                    } else if ((e.key === 'ArrowUp' || e.key === 'ArrowLeft') && closestIndex > 0) {
                        targetIndex = closestIndex - 1;
                    }

                    // Scroll to target section inside slides container
                    const targetSection = sections[targetIndex];
                    slidesContainer.scrollTo({
                        top     : targetSection.offsetTop,
                        behavior: 'smooth'
                    });

                    setTimeout(() => {
                        isScrolling = false;
                    }, 600);
                });
            });
        </script>
    @endpush

    <style>
        .slides {
            /*scroll-snap-type: y mandatory;*/
            overflow-y: scroll;
            height: 100vh;
            scroll-behavior: smooth;
            padding-top: 80px;
            box-sizing: border-box;
        }

        .slides section {
            scroll-snap-align: start;
            scroll-snap-stop: always;
            margin-top: -80px;
        }
    </style>

    <div class="slides">
        <!-- Section 1: Hero -->
        <section id="hero"
                 class="h-screen flex flex-col justify-end items-center text-center bg-cover bg-center relative"
                 style="background-image: url('/imgs/verona/backus_property.jpeg');">
            <div class="relative z-10 px-6 py-4 mb-[15vh] rounded-lg backdrop-blur-sm
                bg-white/70 dark:bg-black/60 animate-on-scroll opacity-0 translate-y-10 transition-all duration-700">
                <h1 class="text-6xl md:text-8xl font-extrabold mb-4 tracking-wide font-heading">
                    Backus Property Development
                </h1>
            </div>
            <div class="absolute bottom-24 inset-x-0 flex flex-col items-center
            animate-on-scroll opacity-0 translate-y-10 transition-all duration-700">

                <!-- Desktop / Tablet Hint -->
                <div class="hidden md:flex bg-white/80 dark:bg-black/70
                text-gray-900 dark:text-gray-100
                px-5 py-2.5 rounded-full text-sm md:text-base
                backdrop-blur-md shadow-md">
                    <span class="font-medium tracking-wide">Press ↑ ↓ or ← → to explore</span>
                </div>

                <!-- Mobile Hint -->
                <div class="flex md:hidden bg-white/80 dark:bg-black/70
                text-gray-900 dark:text-gray-100
                px-4 py-2 rounded-full text-sm
                backdrop-blur-md shadow-md items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4 animate-bounce"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                    <span>Scroll down</span>
                </div>
            </div>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                  bg-white dark:bg-gray-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">General Facts</h2>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-blue-200 dark:bg-blue-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">2015 North Neighborhood Plan</h2>
            <img src="/imgs/verona/current_comprehensive_plan.png" class="w-full max-w-6xl h-auto rounded-lg shadow-lg object-contain"/>
            <a class="theme-link-primary text-xl " href="https://www.veronawi.gov/DocumentCenter/View/947/North-Neighborhood-Plan" target="_blank">Comprehensive Plan <i class="fa fa-external-link text-xs opacity-70 group-hover:opacity-100"></i></a>
            <div class="mt-4 md:mt-12 text-2xl md:text-3xl xl:text-4xl font-bold text-blue-800 dark:text-yellow-500 flex flex-col gap-4 xl:gap-6">
                <div>Zoned "Suburban Residential" (yellow) for 15+ years.</div>
                <div>Multi-Family zones are further out towards PD (brown).</div>
                <div>Current home / property owners invested based on long-standing Comprehensive Plan.</div>
            </div>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-emerald-100 dark:bg-emerald-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Community Concerns</h2>
            <div class="flex flex-col md:flex-row flex-wrap items-center justify-center gap-6 w-full max-w-6xl">
                <img src="/imgs/verona/envision_verona_survey_stats.png"
                     class="w-full md:w-[48%] max-w-full h-auto rounded-lg shadow-lg object-contain"
                     alt="Survey Stats"/>

                <img src="/imgs/verona/envision_verona_concerns.png"
                     class="w-full md:w-[48%] max-w-full h-auto rounded-lg shadow-lg object-contain"
                     alt="Community Concerns"/>
            </div>
            <a class="theme-link-primary text-xl" href="https://engagemksk.mysocialpinpoint.com/verona-comprehensive-plan" target="_blank">Envision Verona <i class="fa fa-external-link text-xs opacity-70 group-hover:opacity-100"></i></a>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-amber-100 dark:bg-amber-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Single Family Homes</h2>
            <div class="flex flex-col md:flex-row flex-wrap items-center justify-center gap-4 md:gap-6 w-full max-w-6xl">
                <img src="/imgs/verona/envision_verona_single_family.png"
                     class="w-[80%] md:w-[48%] max-w-full h-auto rounded-lg object-contain sm:max-h-[50vh]"
                     alt="Single Family Homes"/>

                <img src="/imgs/verona/envision_verona_housing_types.png"
                     class="w-[80%] md:w-[48%] max-w-full h-auto rounded-lg object-contain sm:max-h-[50vh]"
                     alt="Housing Types"/>
            </div>
            <div class="text-black text-2xl mt-6 font-bold">0% housing vacancy rate -vs- 7.6% rental vacancy rate</div>
            <div class="mt-4 md:mt-12 text-lg md:text-3xl xl:text-4xl font-bold text-blue-800 dark:text-yellow-500 flex flex-col gap-12 max-w-6xl">
                Strategy: Promote ‘move-up’ housing by building mid-size homes, enabling current owners to upgrade and free smaller homes for new buyers.
            </div>
            <a class="theme-link-primary text-xl" href="{{route('reports.analysis', 'housing-affordability-analysis')}}" target="_blank">Housing Affordability Analysis <i class="fa fa-external-link text-xs opacity-70 group-hover:opacity-100"></i></a>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-rose-100 dark:bg-rose-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Multi Family Homes</h2>
            <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-12 w-full max-w-6xl">
                <img src="/imgs/verona/envision_verona_multi_family.png"
                     class="w-[80%] md:w-[48%] max-w-full h-auto rounded-lg shadow-md object-contain"
                     alt="Multi Family Homes"/>
                <div class="mt-2 md:mt-0 md:text-lg lg:text-2xl font-semibold text-blue-800 dark:text-yellow-100 flex flex-col gap-4 md:gap-8 text-left md:text-left max-w-xl">
                    <div>MFU Growth != Cheaper Rents</div>
                    <div>Rents are rising (19%) even though Verona is in the top 1% nationally in MFU growth.</div>
                    <div>Unit mix matters: 1BR/studios are cheaper but don’t meet the needs of families needing 2–3+ bedrooms.</div>
                    <div>
                        <a class="theme-link-primary" href="https://www.sciencedirect.com/science/article/abs/pii/S0094119021000656" target="_blank">
                            National research <i class="fa fa-external-link text-xs opacity-70 group-hover:opacity-100"></i>
                        </a>
                        finds that new market-rate construction slightly lowers city-wide rents, but effects are small and depend on unit type and location. Supply helps but doesn’t guarantee affordability.
                    </div>
                    <div>Rapid MFU growth can create secondary issues.</div>
                </div>
            </div>
            <a class="theme-link-primary text-xl" href="https://hdp-us-prod-app-mksk-engage-files.s3.us-west-2.amazonaws.com/8117/5407/5781/Envision_Verona_State_of_the_Community_Report_PART_3.pdf" target="_blank">Envision Verona <i class="fa fa-external-link text-xs opacity-70 group-hover:opacity-100"></i></a>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-blue-100 dark:bg-blue-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Population Model</h2>
            <div class="flex flex-col lg:flex-row items-center justify-center gap-8 md:gap-12 w-full max-w-6xl">
                <img src="/imgs/verona/population_chart.png" class="w-[80%] lg:w-[55%] max-w-full h-auto rounded-lg shadow-md object-contain" alt="Population Chart"/>
                <div class="mt-2 md:mt-0 text-lg md:text-2xl font-semibold text-blue-800 dark:text-yellow-400 flex flex-col gap-2 md:gap-8 text-left md:text-left">
                    <div>
                        <h4 class="underline font-bold">Epic Systems Growth Reaches "Maturity"</h4>
                        <ul class="list-disc ml-5 text-sm sm:text-base md:text-xl">
                            <li>Fewer hires as company matures</li>
                            <li>AI & automation impact</li>
                            <li>High turnover persists (20-30%)</li>
                            <li>Campus expansion completes (2030)</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="underline font-bold">Housing & Infrastructure Reach Capacity</h4>
                        <ul class="list-disc ml-5 text-sm sm:text-base md:text-xl">
                            <li>Landlocked, limited annexation (190 acres)</li>
                            <li>School capacity constraints</li>
                            <li>Cost of living may push out younger employees</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="underline font-bold">Demographics & Economic Feedback Loops</h4>
                        <ul class="list-disc ml-5 text-sm sm:text-base md:text-xl">
                            <li>Aging population</li>
                            <li>Early employees in 40s-50s, fewer children</li>
                            <li>Job saturation in high-skilled roles, similar to Silicon Valley</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-emerald-100 dark:bg-emerald-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Pricing Model</h2>
            <div class="my-6 text-xl sm:text-2xl md:text-4xl italic font-bold text-orange-500 dark:text-red-500 flex flex-col gap-4 max-w-6xl">
                Epic, Exact Sciences, Rayovac, Insurance Companies, Med Tech create a lot of high-paying jobs for this area.
            </div>
            <div class="flex flex-col lg:flex-row items-center justify-center gap-8 md:gap-12 w-full max-w-6xl">
                <img src="/imgs/verona/pricing_model.png" class="w-[80%] lg:w-[55%] max-w-full h-auto rounded-lg shadow-md object-contain"/>
                <div class="mt-12 text-xl sm:text-2xl md:text-4xl font-bold text-orange-500 dark:text-yellow-500 flex flex-col gap-4">
                    <div>Supply & Demand set the equilibrium price.</div>
                    <div>Demand is bounded by regional income levels.</div>
                    <div>Income explains most of elevated costs.</div>
                </div>
            </div>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-blue-100 dark:bg-blue-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Actual Vacancy Rates</h2>
            <img src="/imgs/verona/actual_vacancy_rates.png" class="w-full max-w-6xl h-auto rounded-lg shadow-lg object-contain"/>
            <div class="my-6 text-xl md:text-2xl italic font-bold text-orange-500 dark:text-red-500 flex flex-col gap-4 max-w-6xl">
                A good apartment vacancy rate typically falls between 5% to 10%, indicating a balance between available rental units and demand.
                Rates below 5% may suggest a shortage of rental options, while rates above 10% could indicate potential issues with the property or market.
            </div>
            <div class="text-white text-2xl mt-6 font-bold"><a class="hover:underline" href="{{route('reports.analysis', 'housing-affordability-analysis')}}">0% housing vacancy rate -vs- 7.6% rental vacancy rate</a></div>

        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                  bg-white dark:bg-gray-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Backus Property Project</h2>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-amber-100 dark:bg-amber-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Surrounding Land Use</h2>
            <div class="flex flex-col items-center justify-center gap-8 md:gap-12 w-full max-w-8xl">
                <img src="/imgs/verona/backus_property.jpeg" class="w-full max-w-4xl h-auto rounded-lg shadow-md object-contain"/>
                <div class="mt-2 md:mt-0 text-lg md:text-2xl font-semibold text-blue-800 dark:text-yellow-400 flex flex-col gap-2 md:gap-8 text-left md:text-left">
                    <ul class="list-disc ml-5 space-y-2 sm:space-y-2">
                        <li>Adjacent properties are all single-family homes</li>
                        <li>West & South-West of project consists of mostly single-family homes</li>
                        <li>There are a few multi-family units east of Hwy-M</li>
                        <li>North-East of project is slated for mixed / commercial development</li>
                        <li>North & North-West of project planned for multi-family units</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-rose-100 dark:bg-rose-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Expectations & Alignment</h2>
            <div class="flex flex-col items-center justify-center gap-8 md:gap-12 w-full max-w-6xl">
                <img src="/imgs/verona/expectations.png" class="w-full max-w-4xl h-auto rounded-lg shadow-md object-contain"/>
                <div class="mt-2 md:mt-0 text-lg md:text-2xl font-semibold text-blue-800 dark:text-yellow-400 flex flex-col gap-2 md:gap-8 text-left md:text-left">
                    <ul class="list-disc ml-5 space-y-2 sm:space-y-4">
                        <li>Expected single-family homes based on long-standing future zoning in Comprehensive Plan</li>
                        <li>Multi-family units are expected closer to Hwy-M and Hwy-PD corridors</li>
                    </ul>
                    <div class="md:text-4xl text-white dark:text-black font-bold">
                        Development does not align with: <span class="underline">Comprehensive Plan</span> or <span class="underline">Envision Verona Data</span>!
                    </div>
                </div>
            </div>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                  bg-gray-100 dark:bg-gray-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Expected View</h2>
            <img src="/imgs/verona/expected_view.jpeg" class="w-full max-w-4xl h-auto rounded-lg shadow-md object-contain"/>
            <div class="my-6 text-xl sm:text-2xl md:text-4xl italic font-bold text-orange-500 dark:text-red-500 flex flex-col gap-4 max-w-6xl">
                Distance: ~160 feet<br/>Two images from same distance with alignment at expected spot based on site plan provided.
            </div>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-emerald-100 dark:bg-emerald-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Traffic Patterns</h2>
            <img src="/imgs/verona/traffic_patterns.png" class="w-full max-w-4xl h-auto rounded-lg shadow-md object-contain"/>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-amber-100 dark:bg-amber-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Original Plans Showing Single Family</h2>
            <img src="/imgs/verona/original_plans.jpeg" class="w-full max-w-4xl h-auto rounded-lg shadow-md object-contain"/>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                  bg-white dark:bg-gray-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Reasons to Reject Project</h2>
            <ul class="text-left text-base sm:text-lg md:text-2xl lg:text-2xl xl:text-3xl mb-8 list-none list-inside space-y-2 sm:space-y-4 md:space-y-6">
                <li>👉 Reject because it does not align with long-standing Comprehensive Plan</li>
                <li>👉 Reject because it does not align with data from Envision Verona Project</li>
                <li>👉 Reject because it negatively impacts surrounding neighbors</li>
                <li>👉 Reject because of "too many apartments" concerns</li>
                <li>👉 Reject because community wants to develop more mid-size starter homes</li>
                <li>👉 Reject because you want to create starter home vacancies by providing "move up" opportunities</li>
                <li>👉 Reject to prevent adjacent property values from declining 10-15%</li>
                <li>👉 Reject to prevent flooding of properties in low area</li>
                <li>👉 Reject because land owner's report already indicates it is suited for single-family homes</li>
                <li>👉 Reject because the site plan doesn't integrate well into the neighborhood</li>
                <li>👉 Reject because it is the right thing to do based on data-driven analysis</li>
                <li>👉 Reject because the site plan will impact the natural habitat for cranes</li>
            </ul>
        </section>
    </div>

</x-layouts.app>
