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
                        <div class=" text-xl text-yellow-500">Verona should use zoning and local incentives to boost mixed-income affordable housing.</div>
                        <x-report.ul-with-header title="Key Takeaways:">
                            <li>The “big squeeze” of demand — lower-income and moderate-income households competing with higher-income households for too few units — is not just a Madison problem. Verona likely feels some of that pressure too, especially as Dane County grows.</li>
                            <li>Many of Verona’s workers — school teachers, public-service staff, health-care workers, young adults, retirees — likely fall into the income bands (30-80% AMI) that struggle with affordability.</li>
                            <li>If Verona does little or nothing, the county-wide deficit of needed affordable units (1,765/year) will continue to shrink — meaning continued housing-cost burden, reduced workforce stability, possible displacement or longer commutes, etc.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <hr/>

                <div>
                    <h4 class="text-xl font-bold mb-3">Summary</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <x-report.ul-with-header>
                            <li><span class="font-bold">Definition of affordable housing:</span> The fact sheet defines “affordable housing” as housing where a household spends no more than 30% of its gross monthly income on housing costs (rent + utilities). If a household spends over 30%, they’re “cost-burdened”; over 50% is “severely cost-burdened.”</li>
                            <li><span class="font-bold">Current need in Dane County:</span> There is a large shortage. The fact sheet notes that to meet demand, the county needs to produce 1,765 new affordable units annually — roughly 1,000 more units per year than are currently being produced.</li>
                            <li><span class="font-bold">Who needs affordable housing:</span> The shortage affects a broad swath of the population — not just traditional “low-income” households — including families, seniors, working adults/employees (teachers, health care providers, mechanics, etc.), young adults, veterans, and more.</li>
                            <li><span class="font-bold">Affordability thresholds used:</span> Affordable multifamily housing that meets the RHS goals is typically targeted at households earning between 30% and 80% of Area Median Income (AMI) (i.e., “mixed-income” developments).</li>
                            <li><span class="font-bold">Cost example:</span> For Dane County, 60% AMI for a family of four is given as $75,540/year. For such a household, a two-bedroom unit’s rent + utilities (i.e. affordable threshold) should be no more than $1,700/month.</li>
                            <li><span class="font-bold">How new affordable housing is funded:</span> Affordable multifamily developments are made possible through public subsidies — state and local funding, gap financing, land deals, tax-increment financing (TIF), bonding, etc.</li>
                            <li><span class="font-bold">Causes of the shortage:</span> RHS points out that there are multiple root causes: insufficient housing supply (too few units built), rising development costs, and stagnating wages (household incomes not keeping up with housing costs).</li>
                            <li><span class="font-bold">Recommended municipal strategies:</span> The fact sheet outlines a menu of policy/tools that municipalities can adopt to support affordable housing — for example: including affordable-housing goals in comprehensive plans; using TIF or bond financing; expedited permitting; adjusting zoning (e.g., relaxing multi-family caps, offering density bonuses); reducing impact fees or parking requirements for affordable projects; acquiring land for affordable developments.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

            </div>

        </div>

    </section>

</x-layouts.app>
