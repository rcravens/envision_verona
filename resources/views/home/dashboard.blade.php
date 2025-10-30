<x-app-layout>
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
            scroll-snap-type: y mandatory;
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
                 style="background-image: url('/imgs/hometown_usa.jpg');">
            <div class="relative z-10 px-6 py-4 mb-[15vh] rounded-lg backdrop-blur-sm
                bg-white/70 dark:bg-black/60 animate-on-scroll opacity-0 translate-y-10 transition-all duration-700">
                <h1 class="text-6xl md:text-8xl font-extrabold mb-4 tracking-wide font-heading">
                    Envision Verona
                </h1>
                <p class="text-lg md:text-xl text-gray-800 dark:text-gray-300 font-body">
                    Know Your City. Plan Its Future.
                </p>
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
            <img src="imgs/current_comprehensive_plan.png" class="w-full max-w-6xl h-auto rounded-lg shadow-lg object-contain"/>
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
                <img src="imgs/envision_verona_survey_stats.png"
                     class="w-full md:w-[48%] max-w-full h-auto rounded-lg shadow-lg object-contain"
                     alt="Survey Stats"/>

                <img src="imgs/envision_verona_concerns.png"
                     class="w-full md:w-[48%] max-w-full h-auto rounded-lg shadow-lg object-contain"
                     alt="Community Concerns"/>
            </div>
            <a class="theme-link-primary text-xl" href="https://engagemksk.mysocialpinpoint.com/verona-comprehensive-plan" target="_blank">Envision Verona <i class="fa fa-external-link text-xs opacity-70 group-hover:opacity-100"></i></a>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-amber-100 dark:bg-amber-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Single Family Homes</h2>
            <div class="flex flex-col md:flex-row flex-wrap items-center justify-center gap-4 md:gap-6 w-full max-w-6xl">
                <img src="imgs/envision_verona_single_family.png"
                     class="w-[80%] md:w-[48%] max-w-full h-auto rounded-lg object-contain sm:max-h-[50vh]"
                     alt="Single Family Homes"/>

                <img src="imgs/envision_verona_housing_types.png"
                     class="w-[80%] md:w-[48%] max-w-full h-auto rounded-lg object-contain sm:max-h-[50vh]"
                     alt="Housing Types"/>
            </div>
            <div class="mt-4 md:mt-12 text-lg md:text-3xl xl:text-4xl font-bold text-blue-800 dark:text-yellow-500 flex flex-col gap-12 max-w-6xl">
                Strategy: Promote ‘move-up’ housing by building mid-size homes, enabling current owners to upgrade and free smaller homes for new buyers.
            </div>
            <a class="theme-link-primary text-xl" href="https://hdp-us-prod-app-mksk-engage-files.s3.us-west-2.amazonaws.com/8117/5407/5781/Envision_Verona_State_of_the_Community_Report_PART_3.pdf" target="_blank">Envision Verona <i class="fa fa-external-link text-xs opacity-70 group-hover:opacity-100"></i></a>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-rose-100 dark:bg-rose-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Multi Family Homes</h2>
            <div class="flex flex-col md:flex-row items-center justify-center gap-8 md:gap-12 w-full max-w-6xl">
                <img src="imgs/envision_verona_multi_family.png"
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
                <img src="imgs/population_chart.png" class="w-[80%] lg:w-[55%] max-w-full h-auto rounded-lg shadow-md object-contain" alt="Population Chart"/>
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
                <img src="imgs/pricing_model.png" class="w-[80%] lg:w-[55%] max-w-full h-auto rounded-lg shadow-md object-contain"/>
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
            <img src="imgs/actual_vacancy_rates.png" class="w-full max-w-6xl h-auto rounded-lg shadow-lg object-contain"/>
            <div class="my-6 text-xl md:text-2xl italic font-bold text-orange-500 dark:text-red-500 flex flex-col gap-4 max-w-6xl">
                A good apartment vacancy rate typically falls between 5% to 10%, indicating a balance between available rental units and demand.
                Rates below 5% may suggest a shortage of rental options, while rates above 10% could indicate potential issues with the property or market.
            </div>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                  bg-white dark:bg-gray-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Backus Property Project</h2>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-amber-100 dark:bg-amber-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Surrounding Land Use</h2>
            <div class="flex flex-col items-center justify-center gap-8 md:gap-12 w-full max-w-8xl">
                <img src="imgs/backus_property.jpeg" class="w-full max-w-4xl h-auto rounded-lg shadow-md object-contain"/>
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
                <img src="imgs/expectations.png" class="w-full max-w-4xl h-auto rounded-lg shadow-md object-contain"/>
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
            <img src="imgs/expected_view.jpeg" class="w-full max-w-4xl h-auto rounded-lg shadow-md object-contain"/>
            <div class="my-6 text-xl sm:text-2xl md:text-4xl italic font-bold text-orange-500 dark:text-red-500 flex flex-col gap-4 max-w-6xl">
                Distance: ~160 feet<br/>Two images from same distance with alignment at expected spot based on site plan provided.
            </div>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-emerald-100 dark:bg-emerald-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Traffic Patterns</h2>
            <img src="imgs/traffic_patterns.png" class="w-full max-w-4xl h-auto rounded-lg shadow-md object-contain"/>
        </section>

        <section class="h-screen flex flex-col justify-center items-center text-center px-6
                bg-amber-100 dark:bg-amber-900 animate-on-scroll opacity-0 translate-y-10">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 font-heading">Original Plans Showing Single Family</h2>
            <img src="imgs/original_plans.jpeg" class="w-full max-w-4xl h-auto rounded-lg shadow-md object-contain"/>
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

        <!-- Section: Population Growth Over Time -->
        {{--    <section class="h-screen flex flex-col justify-center items-center text-center px-6 py-8--}}
        {{--                bg-gray-100 dark:bg-gray-800">--}}
        {{--        <h2 class="text-4xl font-bold mb-12">Interactive Population Projection</h2>--}}

        {{--        <div class="flex flex-wrap gap-4 justify-center mb-6">--}}
        {{--            <label>--}}
        {{--                City Max Population:--}}
        {{--                <input type="number" id="inputK" value="50000" min="10000" step="1000" placeholder="45000"--}}
        {{--                       class="border rounded px-2 py-1--}}
        {{--                bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100--}}
        {{--                border-gray-300 dark:border-gray-600--}}
        {{--                focus:outline-none focus:ring-2 focus:ring-blue-400"/>--}}
        {{--            </label>--}}

        {{--            <label>--}}
        {{--                Initial Growth Rate 2025 (%):--}}
        {{--                <input type="number" id="rInit" value="4" min="0" max="10" step="0.1" placeholder="3"--}}
        {{--                       class="border rounded px-2 py-1--}}
        {{--                bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100--}}
        {{--                border-gray-300 dark:border-gray-600--}}
        {{--                focus:outline-none focus:ring-2 focus:ring-blue-400"/>--}}
        {{--            </label>--}}

        {{--            <label>--}}
        {{--                Final Growth Rate 2050 (%):--}}
        {{--                <input type="number" id="rFinal" value="2" min="0" max="10" step="0.1" placeholder="1.5"--}}
        {{--                       class="border rounded px-2 py-1--}}
        {{--                bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100--}}
        {{--                border-gray-300 dark:border-gray-600--}}
        {{--                focus:outline-none focus:ring-2 focus:ring-blue-400"/>--}}
        {{--            </label>--}}

        {{--            <button id="updateChart" class="bg-blue-500 text-white px-4 py-2 rounded">Update Chart</button>--}}
        {{--        </div>--}}

        {{--        <div class="w-full max-w-4xl h-[60vh]">--}}
        {{--            <canvas id="populationGrowth" class="w-full h-full" style="background-color: #fff"></canvas>--}}
        {{--            <button id="openModelInfo"--}}
        {{--                    class="mt-4 text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">--}}
        {{--                ℹ️ Model Information--}}
        {{--            </button>--}}

        {{--            <!-- Modal -->--}}
        {{--            <div id="modelInfoModal"--}}
        {{--                 class="fixed inset-0 hidden bg-black bg-opacity-60 z-50 flex items-center justify-center">--}}
        {{--                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg w-11/12 max-w-lg p-6--}}
        {{--                text-gray-800 dark:text-gray-200 overflow-y-auto max-h-[80vh]">--}}
        {{--                    <!-- Close button -->--}}
        {{--                    <button id="closeModelInfo"--}}
        {{--                            class="absolute top-2 right-3 text-gray-500 hover:text-gray-800 dark:hover:text-gray-100 text-2xl leading-none">--}}
        {{--                        &times;--}}
        {{--                    </button>--}}

        {{--                    <h3 class="text-lg font-semibold mb-2">Model Information</h3>--}}
        {{--                    <p class="text-sm leading-relaxed">--}}
        {{--                        The projected population curve shown above is based on a--}}
        {{--                        <em>capped linear-percent growth model</em>. The model assumes that population increases--}}
        {{--                        approximately linearly each year by a fixed percentage of the 2025 baseline population.--}}
        {{--                        Growth gradually slows as the total population approaches the city’s--}}
        {{--                        <em>City Max Population</em> — the estimated maximum sustainable population based on land--}}
        {{--                        availability, infrastructure, and housing limits.--}}
        {{--                        <br/><br/>--}}
        {{--                        Mathematically, this model is represented as:--}}
        {{--                        <code class="block text-xs md:text-sm bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded px-2 py-1 mt-2">--}}
        {{--                            P(t) = K − (K − P₀) · exp(−(r·P₀/K)·(t − t₀))--}}
        {{--                        </code>--}}
        {{--                        where <strong>P₀</strong> is the population at the base year (<strong>t₀ = 2025</strong>),--}}
        {{--                        <strong>r</strong> is the initial annual growth rate, and <strong>K</strong> is the city’s--}}
        {{--                        maximum population capacity. As <strong>P(t)</strong> approaches <strong>K</strong>,--}}
        {{--                        the effective growth rate declines naturally, reflecting reduced available land and a maturing job market.--}}
        {{--                    </p>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        @push('scripts')--}}
        {{--            <script type="module">--}}
        {{--                document.getElementById('openModelInfo').onclick = () =>--}}
        {{--                    document.getElementById('modelInfoModal').classList.remove('hidden');--}}
        {{--                document.getElementById('closeModelInfo').onclick = () =>--}}
        {{--                    document.getElementById('modelInfoModal').classList.add('hidden');--}}

        {{--                let chart; // global chart reference--}}

        {{--                function createPopulationChart(K, rInit, rFinal) {--}}
        {{--                    // Convert percentages to decimals--}}
        {{--                    rInit = rInit / 100;--}}
        {{--                    rFinal = rFinal / 100;--}}

        {{--                    const years = [1960, 1970, 1980, 1990, 2000, 2010, 2020, 2025];--}}
        {{--                    const historical = [1947, 2334, 3208, 5200, 7052, 10619, 14030, 16961];--}}

        {{--                    const projectedYears = [];--}}
        {{--                    for (let y = 2030; y <= 2050; y += 5) projectedYears.push(y);--}}

        {{--                    const P0 = historical[historical.length - 1];--}}
        {{--                    const Y0 = years[years.length - 1];--}}

        {{--                    function cappedLinearPercentAsymptotic(lastP, lastY, r, year) {--}}
        {{--                        const t = year - lastY; // years since initial--}}
        {{--                        if (P0 >= K) return Math.round(K);--}}

        {{--                        const lambda = (r * lastP) / K;           // decay rate in closed form--}}
        {{--                        const Pt = K - (K - lastP) * Math.exp(-lambda * t);--}}
        {{--                        return Math.round(Math.min(Pt, K));--}}
        {{--                    }--}}

        {{--                    // Optimistic: constant rHigh--}}
        {{--                    let last_P = P0;--}}
        {{--                    let last_Y = Y0;--}}
        {{--                    const optimisticProjection = projectedYears.map(year => {--}}
        {{--                        const r = rInit + (rFinal - rInit) * (year - 2030) / (2050 - 2030);--}}
        {{--                        last_P = cappedLinearPercentAsymptotic(last_P, last_Y, r, year);--}}
        {{--                        last_Y = year;--}}
        {{--                        return last_P;--}}
        {{--                    });--}}


        {{--                    const historicalDataPoints = years.map((y, i) => ({x: y, y: historical[i]}));--}}
        {{--                    const optimisticDataPoints = projectedYears.map((y, i) => ({x: y, y: optimisticProjection[i]}));--}}

        {{--                    const optimisticConnector = [--}}
        {{--                        {x: 2025, y: P0},--}}
        {{--                        {x: 2030, y: optimisticProjection[0]}--}}
        {{--                    ];--}}

        {{--                    const data = {--}}
        {{--                        datasets: [--}}
        {{--                            {label: 'Historical Population', data: historicalDataPoints, borderColor: '#0b3af5', fill: false, tension: 0, pointRadius: 4},--}}
        {{--                            {label: 'Projection', data: optimisticDataPoints, borderColor: '#0b3af5', fill: false, borderDash: [5, 5], tension: 0, pointRadius: 4},--}}
        {{--                            {label: 'Connector 2025→2030', data: optimisticConnector, borderColor: '#0b3af5', fill: false, borderDash: [5, 5], pointRadius: 0, tension: 0},--}}
        {{--                        ]--}}
        {{--                    };--}}

        {{--                    const options = {--}}
        {{--                        responsive: true,--}}
        {{--                        plugins   : {--}}
        {{--                            legend: {--}}
        {{--                                display: true,--}}
        {{--                                labels : {usePointStyle: true, filter: item => !item.text.includes('Connector')}--}}
        {{--                            }--}}
        {{--                        },--}}
        {{--                        scales    : {--}}
        {{--                            x: {type: 'linear', title: {display: true, text: 'Year'}, ticks: {stepSize: 5}},--}}
        {{--                            y: {beginAtZero: true, title: {display: true, text: 'Population'}}--}}
        {{--                        }--}}
        {{--                    };--}}

        {{--                    if (chart) {--}}
        {{--                        chart.data = data;--}}
        {{--                        chart.options = options;--}}
        {{--                        chart.update();--}}
        {{--                    } else {--}}
        {{--                        chart = new Chart(document.getElementById('populationGrowth'), {type: 'line', data, options});--}}
        {{--                    }--}}
        {{--                }--}}

        {{--                // Initial chart--}}
        {{--                function updatePopulationChart() {--}}
        {{--                    const K = parseInt(document.getElementById('inputK').value);--}}
        {{--                    const rInit = parseFloat(document.getElementById('rInit').value);--}}
        {{--                    const rFinal = parseFloat(document.getElementById('rFinal').value);--}}
        {{--                    createPopulationChart(K, rInit, rFinal);--}}
        {{--                }--}}

        {{--                updatePopulationChart(60000, 4, 4);--}}

        {{--                // Update on user input--}}
        {{--                document.getElementById('updateChart').addEventListener('click', () => {--}}
        {{--                    updatePopulationChart();--}}
        {{--                });--}}
        {{--            </script>--}}
        {{--        @endpush--}}
        {{--    </section>--}}

        {{--    <section class="h-screen flex flex-col justify-center items-center text-center px-6--}}
        {{--                  bg-white dark:bg-gray-900 animate-on-scroll opacity-0 translate-y-10">--}}
        {{--        <h2 class="text-4xl md:text-5xl font-bold mb-6 font-heading">Coming Soon</h2>--}}
        {{--        <ul class="text-lg md:text-xl text-gray-700 dark:text-gray-300 space-y-4 font-body list-disc list-inside">--}}
        {{--            <li>House / Rent Cost Analysis</li>--}}
        {{--            <li>House / Rent Vacancy Rates</li>--}}
        {{--            <li>Upcoming Project Impact Analysis</li>--}}
        {{--            <li>Community Survey Results</li>--}}
        {{--        </ul>--}}
        {{--    </section>--}}
    </div>

</x-app-layout>
