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

                // Keyboard slide navigation — fixed using offsetTop
                const sections = Array.from(document.querySelectorAll('section'));
                let isScrolling = false;

                document.addEventListener('keydown', (e) => {
                    if (!['ArrowDown', 'ArrowRight', 'ArrowUp', 'ArrowLeft'].includes(e.key)) return;
                    e.preventDefault();
                    if (isScrolling) return;
                    isScrolling = true;

                    // Find section whose top is closest to viewport top
                    let closestIndex = 0;
                    let minDistance = Infinity;
                    sections.forEach((sec, idx) => {
                        const distance = Math.abs(sec.getBoundingClientRect().top);
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

                    // Scroll smoothly to the target section using offsetTop
                    const targetSection = sections[targetIndex];
                    const scrollTop = targetSection.offsetTop;

                    window.scrollTo({
                        top     : scrollTop,
                        behavior: 'smooth'
                    });

                    // Release scroll lock after smooth scroll (~duration 500ms)
                    setTimeout(() => {
                        isScrolling = false;
                    }, 600);
                });
            });
        </script>
    @endpush

    <style>
        html, body {
            scroll-behavior: smooth;
        }
    </style>

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
    </section>

    <!-- Section: Population Growth Over Time -->
    <section class="h-screen flex flex-col justify-center items-center text-center px-6 py-8
                bg-gray-100 dark:bg-gray-800">
        <h2 class="text-4xl font-bold mb-12">Interactive Population Projection</h2>

        <div class="flex flex-wrap gap-4 justify-center mb-6">
            <label>
                City Max Population:
                <input type="number" id="inputK" value="50000" min="10000" step="1000" placeholder="45000"
                       class="border rounded px-2 py-1
                bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100
                border-gray-300 dark:border-gray-600
                focus:outline-none focus:ring-2 focus:ring-blue-400"/>
            </label>

            <label>
                Initial Growth Rate 2025 (%):
                <input type="number" id="rInit" value="4" min="0" max="10" step="0.1" placeholder="3"
                       class="border rounded px-2 py-1
                bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100
                border-gray-300 dark:border-gray-600
                focus:outline-none focus:ring-2 focus:ring-blue-400"/>
            </label>

            <label>
                Final Growth Rate 2050 (%):
                <input type="number" id="rFinal" value="2" min="0" max="10" step="0.1" placeholder="1.5"
                       class="border rounded px-2 py-1
                bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100
                border-gray-300 dark:border-gray-600
                focus:outline-none focus:ring-2 focus:ring-blue-400"/>
            </label>

            <button id="updateChart" class="bg-blue-500 text-white px-4 py-2 rounded">Update Chart</button>
        </div>

        <div class="w-full max-w-4xl h-[60vh]">
            <canvas id="populationGrowth" class="w-full h-full" style="background-color: #fff"></canvas>
            <button id="openModelInfo"
                    class="mt-4 text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">
                ℹ️ Model Information
            </button>

            <!-- Modal -->
            <div id="modelInfoModal"
                 class="fixed inset-0 hidden bg-black bg-opacity-60 z-50 flex items-center justify-center">
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg w-11/12 max-w-lg p-6
                text-gray-800 dark:text-gray-200 overflow-y-auto max-h-[80vh]">
                    <!-- Close button -->
                    <button id="closeModelInfo"
                            class="absolute top-2 right-3 text-gray-500 hover:text-gray-800 dark:hover:text-gray-100 text-2xl leading-none">
                        &times;
                    </button>

                    <h3 class="text-lg font-semibold mb-2">Model Information</h3>
                    <p class="text-sm leading-relaxed">
                        The projected population curve shown above is based on a
                        <em>capped linear-percent growth model</em>. The model assumes that population increases
                        approximately linearly each year by a fixed percentage of the 2025 baseline population.
                        Growth gradually slows as the total population approaches the city’s
                        <em>City Max Population</em> — the estimated maximum sustainable population based on land
                        availability, infrastructure, and housing limits.
                        <br/><br/>
                        Mathematically, this model is represented as:
                        <code class="block text-xs md:text-sm bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded px-2 py-1 mt-2">
                            P(t) = K − (K − P₀) · exp(−(r·P₀/K)·(t − t₀))
                        </code>
                        where <strong>P₀</strong> is the population at the base year (<strong>t₀ = 2025</strong>),
                        <strong>r</strong> is the initial annual growth rate, and <strong>K</strong> is the city’s
                        maximum population capacity. As <strong>P(t)</strong> approaches <strong>K</strong>,
                        the effective growth rate declines naturally, reflecting reduced available land and a maturing job market.
                    </p>
                </div>
            </div>
        </div>
        @push('scripts')
            <script type="module">
                document.getElementById('openModelInfo').onclick = () =>
                    document.getElementById('modelInfoModal').classList.remove('hidden');
                document.getElementById('closeModelInfo').onclick = () =>
                    document.getElementById('modelInfoModal').classList.add('hidden');

                let chart; // global chart reference

                function createPopulationChart(K, rInit, rFinal) {
                    // Convert percentages to decimals
                    rInit = rInit / 100;
                    rFinal = rFinal / 100;

                    const years = [1960, 1970, 1980, 1990, 2000, 2010, 2020, 2025];
                    const historical = [1947, 2334, 3208, 5200, 7052, 10619, 14030, 16961];

                    const projectedYears = [];
                    for (let y = 2030; y <= 2050; y += 5) projectedYears.push(y);

                    const P0 = historical[historical.length - 1];
                    const Y0 = years[years.length - 1];

                    function cappedLinearPercentAsymptotic(lastP, lastY, r, year) {
                        const t = year - lastY; // years since initial
                        if (P0 >= K) return Math.round(K);

                        const lambda = (r * lastP) / K;           // decay rate in closed form
                        const Pt = K - (K - lastP) * Math.exp(-lambda * t);
                        return Math.round(Math.min(Pt, K));
                    }

                    // Optimistic: constant rHigh
                    let last_P = P0;
                    let last_Y = Y0;
                    const optimisticProjection = projectedYears.map(year => {
                        const r = rInit + (rFinal - rInit) * (year - 2030) / (2050 - 2030);
                        last_P = cappedLinearPercentAsymptotic(last_P, last_Y, r, year);
                        last_Y = year;
                        return last_P;
                    });


                    const historicalDataPoints = years.map((y, i) => ({x: y, y: historical[i]}));
                    const optimisticDataPoints = projectedYears.map((y, i) => ({x: y, y: optimisticProjection[i]}));

                    const optimisticConnector = [
                        {x: 2025, y: P0},
                        {x: 2030, y: optimisticProjection[0]}
                    ];

                    const data = {
                        datasets: [
                            {label: 'Historical Population', data: historicalDataPoints, borderColor: '#0b3af5', fill: false, tension: 0, pointRadius: 4},
                            {label: 'Projection', data: optimisticDataPoints, borderColor: '#0b3af5', fill: false, borderDash: [5, 5], tension: 0, pointRadius: 4},
                            {label: 'Connector 2025→2030', data: optimisticConnector, borderColor: '#0b3af5', fill: false, borderDash: [5, 5], pointRadius: 0, tension: 0},
                        ]
                    };

                    const options = {
                        responsive: true,
                        plugins   : {
                            legend: {
                                display: true,
                                labels : {usePointStyle: true, filter: item => !item.text.includes('Connector')}
                            }
                        },
                        scales    : {
                            x: {type: 'linear', title: {display: true, text: 'Year'}, ticks: {stepSize: 5}},
                            y: {beginAtZero: true, title: {display: true, text: 'Population'}}
                        }
                    };

                    if (chart) {
                        chart.data = data;
                        chart.options = options;
                        chart.update();
                    } else {
                        chart = new Chart(document.getElementById('populationGrowth'), {type: 'line', data, options});
                    }
                }

                // Initial chart
                function updatePopulationChart() {
                    const K = parseInt(document.getElementById('inputK').value);
                    const rInit = parseFloat(document.getElementById('rInit').value);
                    const rFinal = parseFloat(document.getElementById('rFinal').value);
                    createPopulationChart(K, rInit, rFinal);
                }

                updatePopulationChart(60000, 4, 4);

                // Update on user input
                document.getElementById('updateChart').addEventListener('click', () => {
                    updatePopulationChart();
                });
            </script>
        @endpush
    </section>

    <section class="h-screen flex flex-col justify-center items-center text-center px-6
                  bg-white dark:bg-gray-900 animate-on-scroll opacity-0 translate-y-10">
        <h2 class="text-4xl md:text-5xl font-bold mb-6 font-heading">Coming Soon</h2>
        <ul class="text-lg md:text-xl text-gray-700 dark:text-gray-300 space-y-4 font-body list-disc list-inside">
            <li>House / Rent Cost Analysis</li>
            <li>House / Rent Vacancy Rates</li>
            <li>Upcoming Project Impact Analysis</li>
            <li>Community Survey Results</li>
        </ul>
    </section>

</x-app-layout>
