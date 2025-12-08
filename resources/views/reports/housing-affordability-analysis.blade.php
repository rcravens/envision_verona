<x-layouts.app>

    <!-- Section: Population Growth Over Time -->
    <section class="min-h-screen py-8 bg-gray-100 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto">
            <div class="mb-12 flex flex-row items-center justify-between">
                <h1 class="text-4xl font-bold">{{$report->name}} ({{$report->year}}) <a href="{{$report->url}}" class="text-base text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" target="_blank">view</a></h1>
                <h2 class="text-2xl">Report Analysis</h2>
            </div>

            <div class="flex flex-col gap-8">
                <div class="p-4 bg-white dark:bg-gray-900 rounded-lg">
                    <h4 class="text-xl font-bold mb-3">Recommendation Based on the Report</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <div class=" text-4xl text-yellow-500">Near-Term Priority: Encourage more homes for ownership (detached, duplex, townhome, condo).</div>
                        <x-report.ul-with-header title="Rationale:">
                            <li>Zero vacancy in owner housing</li>
                            <li>Insufficient supply for 80–120% AMI households</li>
                            <li>Middle-income households are putting downward pressure on both rental and affordable units</li>
                        </x-report.ul-with-header>
                        <x-report.ul-with-header title="Boosting ownership housing would:">
                            <li>Free up rental units for renters</li>
                            <li>Reduce competition in lower-cost segments</li>
                            <li>Improve affordability across the full housing ladder</li>
                            <li>Support population growth without overheating rents</li>
                        </x-report.ul-with-header>
                        <div class="text-4xl text-yellow-500">Secondary Priority: Continue allowing rental construction, but don’t overcorrect</div>
                        <x-report.ul-with-header>
                            <li>Rental supply is not the main barrier right now, and vacancy is already healthy (7.6%)</li>
                            <li>But the city should not discourage rental construction — it should simply rebalance toward ownership options</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <hr/>

                <div>
                    <h4 class="text-xl font-bold mb-3">Summary</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <x-report.ul-with-header title="Key Insights:">
                            <li><span class="font-bold">Verona is growing quickly</span>, and its housing supply hasn’t fully kept pace—especially for owner-occupied homes.</li>
                            <li><span class="font-bold">Rents and home prices have risen ~6% per year</span>, making housing less affordable for many residents.</li>
                            <li><span class="font-bold">Rental supply has improved</span>: recent construction has pushed <span class="text-yellow-500">rental vacancy up to ~7.6%, easing pressure on rents</span>.</li>
                            <li><span class="font-bold">Owner-occupied housing is extremely tight</span>: the <span class="text-yellow-500">housing vacancy rate is effectively 0%, driving up home prices and limiting options for buyers</span>.</li>
                            <li><span class="font-bold">Lower-income renters are heavily burdened</span>: ~42% spend more than 30% of their income on housing; ~75% of very low–income households are cost-burdened.</li>
                            <li><span class="font-bold">Projected need</span>: by 2040, Verona will need ~3,400 more housing units (≈190 per year).</li>
                            <li><span class="font-bold">Current policies (phasing limits, conditional-use requirements, fees)</span> may restrict the city’s ability to meet that production target.</li>
                            <li><span class="font-bold">Affordability gaps exist</span>: there is a shortage of units affordable to moderate- and higher-income households (80%+ AMI), causing them to “rent down” and increase competition for lower-cost units.</li>
                        </x-report.ul-with-header>
                        <div class="text-4xl text-yellow-500">1. The rental market is relatively healthy right now</div>
                        <div class="ml-6">
                            <x-report.ul-with-header title="Evidence from the report:">
                                <li>Rental vacancy is 7.6%, far higher than earlier years and in the “balanced” range.</li>
                                <li>A surge in multi-unit construction (265 units in 2022, 403 in 2023) has expanded supply.</li>
                                <li>Most cost-burdened renters are low-income households who won’t be helped simply by building more market-rate rentals.</li>
                            </x-report.ul-with-header>
                            <div>
                                <div class="font-bold underline mb-2">Conclusion:</div>
                                <div><span class="text-yellow-500">The rental market is not currently the main pressure point</span>. Verona has been doing well at adding multi-unit rental supply, and vacancy rates suggest it is keeping up with demand.</div>
                            </div>
                        </div>
                        <div class="text-4xl text-yellow-500">2. The ownership market is extremely tight</div>
                        <div class="ml-6">
                            <x-report.ul-with-header title="Evidence from the report:">
                                <li>Owner-occupied vacancy is effectively 0% — meaning homes for sale are extremely scarce.</li>
                                <li>Detached single-family homes still make up the majority of the market, but very few are being added relative to demand.</li>
                                <li>Middle- and higher-income households face a shortage of units affordable to them (80%+ AMI).</li>
                                <li>These households then “buy down” or rent down, competing with lower-income renters.</li>
                            </x-report.ul-with-header>
                            <div>
                                <div class="font-bold underline mb-2">Conclusion:</div>
                                <div><span class="text-yellow-500">A lack of ownership opportunities is distorting the whole housing ecosystem</span>. More for-sale homes (single-family, townhome, condo) would relieve pressure throughout the market.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>

                <div>
                    <h4 class="text-xl font-bold mb-3">Purpose & Context</h4>
                    <div class="ml-6">
                        <x-report.ul-with-header>
                            <li>The report satisfies requirements under Wis. Stat. § 66.10013, which mandates that certain Wisconsin municipalities track housing stock, development, and affordability.</li>
                            <li>It inventories: new residential lots and units approved, undeveloped parcels zoned for residential, parcels suitable for residential (but not zoned), and analyzes how regulations affect cost of new housing.</li>
                            <li>Verona has experienced rapid growth: between 2010 and 2020, its population increased by ~32%.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-3">Housing Stock & Existing Market</h4>
                    <div class="ml-6">
                        <x-report.ul-with-header>
                            <li>Just over half of all housing units (including vacant) in Verona are detached single-unit homes under owner-occupancy; 28% of occupied households are renters (up from 26% in 2010).</li>
                            <li>Structure-type breakdown of occupied units: ~54% single-unit detached, ~16% single-unit attached (e.g. duplex/townhome), ~30% multi-unit (3+ units).</li>
                            <li>Rental housing (multi-unit) accounts for a majority of renter households: 70% of renters live in 10+ unit buildings.</li>
                            <li class="text-yellow-500">The rental vacancy rate was about 7.6% in 2022 — up from 1.4% in 2015 — which suggests rental construction has been keeping up with demand.</li>
                            <li class="text-red-500">But ownership vacancy is 0% — indicating almost no vacant homes for sale, a sign of tight supply.</li>
                            <li>Over the roughly 7-year period covered (2015 to 2022):
                                <ul class="ml-6 mt-2 list-disc list-inside space-y-2">
                                    <li>Median gross rent increased from $1,008 to $1,407 (about 6% per year).</li>
                                    <li>Median home values rose from roughly $250,000 to $353,000 (also about 6% per year).</li>
                                </ul>
                            </li>
                            <li>As a result, 42% of renter households pay more than 30% of their income toward housing costs — a common threshold for being considered “cost-burdened.”</li>
                            <li>For lower-income households (under 50% of Area Median Family Income), 75% are considered cost-burdened.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-3">Demand Projections & Future Housing Needs</h4>
                    <div class="ml-6">
                        <x-report.ul-with-header>
                            <li>According to the broader Dane County Regional Housing Strategy (2024), Verona is projected to have about 9,115 households by 2040, or ~21,329 people (assuming 2.34 persons/household) under state forecasts.</li>
                            <li>To meet that, Verona would need ~9,442 total housing units by 2040. That means adding roughly 3,404 new units — about 190 units per year.</li>
                            <li>If Verona keeps its existing unit-type mix, that would translate to ~103 new detached single-family homes annually, with the rest in multi-unit housing.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-3">Affordability Gaps & Imbalances</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <div>
                            <table class="mx-auto bg-gray-100 dark:bg-gray-900 text-left">
                                <thead>
                                <tr>
                                    <x-table.th>Income Range</x-table.th>
                                    <x-table.th>Households At Level</x-table.th>
                                    <x-table.th>Affordable Units At Level</x-table.th>
                                    <x-table.th>Surplus</x-table.th>
                                    <x-table.th>Need</x-table.th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <x-table.td><=50% HAMFI</x-table.td>
                                    <x-table.td>1200</x-table.td>
                                    <x-table.td>1170</x-table.td>
                                    <x-table.td>--</x-table.td>
                                    <x-table.td>30</x-table.td>
                                </tr>
                                <tr>
                                    <x-table.td>51%-80% HAMFI</x-table.td>
                                    <x-table.td>890</x-table.td>
                                    <x-table.td>2230</x-table.td>
                                    <x-table.td>1340</x-table.td>
                                    <x-table.td>--</x-table.td>
                                </tr>
                                <tr>
                                    <x-table.td>>=81% HAMFI</x-table.td>
                                    <x-table.td>3650</x-table.td>
                                    <x-table.td>2245</x-table.td>
                                    <x-table.td>--</x-table.td>
                                    <x-table.td>1405</x-table.td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <x-table.th class="font-bold">All</x-table.th>
                                    <x-table.th class="font-bold">5740</x-table.th>
                                    <x-table.th class="font-bold">5645</x-table.th>
                                    <x-table.th class="font-bold">--</x-table.th>
                                    <x-table.th class="font-bold">95</x-table.th>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="text-sm italic text-center">The HUD Area Median Family Income (HAMFI) for a family of 4 was $80,600 annually. (2021)</div>
                        </div>
                        <x-report.ul-with-header>
                            <li>There is a surplus of units affordable to households earning 51–80% of Area Median Income (AMI), and a nearly matching number of units for those under 50% AMI.</li>
                            <li class="text-yellow-500">But there is a significant shortage of units affordable for households at or above 80% AMI — meaning many moderate- and higher-income households are buying or renting “down,” increasing competition for lower-cost housing.</li>
                            <li>This dynamic squeezes lower- and moderate-income households, making it harder for them to find affordable housing — even if some “affordable units” exist.</li>
                            <li>The report notes that some of the City’s policies — notably a “Residential Phasing Plan” that limits how many units can be built each year — may be constraining supply and thereby contributing to affordability challenges.</li>
                            <li>Because of these mechanisms, achieving a balanced, affordable housing market will likely require adjustments: especially if Verona wants to hit future unit-production targets while preserving a mix of housing types and serving a range of income levels.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

            </div>

        </div>

    </section>

</x-layouts.app>
