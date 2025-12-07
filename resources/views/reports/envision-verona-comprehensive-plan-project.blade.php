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
                        <div class=" text-xl text-yellow-500">1. Diversify the Housing Supply (Especially Missing-Middle Housing)</div>
                        <div class="ml-6">A more balanced mix of housing types—such as duplexes, townhomes, cottage courts, small single-family lots, and senior-oriented units—can better serve Verona’s changing demographics. As household sizes shift and the population grows, residents increasingly need housing options beyond large single-family homes or large apartment complexes.</div>
                        <div class="ml-6"><span class="underline">Rationale:</span> Data shows rising renter burden, increasing home prices, and a growing population made up of singles, young adults, and older adults. Missing-middle housing helps fill the affordability gap, supports aging in place, and offers options for local workers who currently commute in from outside the city.</div>
                        <div class=" text-xl text-yellow-500">2. Expand Transit, Biking, and Walking Infrastructure</div>
                        <div class="ml-6">Strengthening Verona’s multimodal network—through more bike lanes, safer crosswalks, trail connections, and improved transit—will address current congestion challenges and enhance everyday mobility. Many neighborhoods lack walkable links to parks, schools, and downtown.</div>
                        <div class="ml-6"><span class="underline">Rationale:</span> Residents consistently express a desire for walkability and bikeability. Crash hotspots and peak-hour congestion show the limits of current road capacity. Non-car transportation options reduce traffic pressure, increase safety, and enhance livability.</div>
                        <div class=" text-xl text-yellow-500">3. Preserve and Enhance Natural Areas, Parks, and Open Space</div>
                        <div class="ml-6">Verona’s green spaces are a defining community asset. Protecting natural areas, expanding parks in underserved neighborhoods, improving trail connectivity, and enhancing habitat areas will help maintain quality of life as the city grows.</div>
                        <div class="ml-6"><span class="underline">Rationale:</span> Public feedback highlights a strong desire to protect natural resources and expand recreation opportunities. The State of the Community reports show uneven access to parks and trails. As development increases, proactive preservation is essential to maintaining community character.</div>
                        <div class=" text-xl text-yellow-500">4. Invest in a Walkable, Vibrant Downtown</div>
                        <div class="ml-6">Downtown Verona can serve as a cultural and economic hub by focusing on mixed-use infill, pedestrian-friendly streetscapes, public gathering spaces, and support for local businesses. Enhancing downtown walkability strengthens its identity and economic vitality.</div>
                        <div class="ml-6"><span class="underline">Rationale:</span> Community input ranks downtown improvements as a top priority. Walkable, vibrant districts attract residents, businesses, and visitors, increasing economic activity. A thriving center also helps Verona retain its small-city feel even as population increases.</div>
                        <div class=" text-xl text-yellow-500">5. Plan for Coordinated, Smart Growth on the City’s Edges</div>
                        <div class="ml-6">Future development areas should integrate mixed-use planning, connected street networks, stormwater management, and open space preservation. Growth near sensitive environmental areas must be intentional and coordinated.</div>
                        <div class="ml-6"><span class="underline">Rationale:</span> Verona still has available land near agricultural and environmentally sensitive zones. Without coordinated planning, growth can become fragmented and costly to serve. Smart growth ensures infrastructure efficiency, environmental protection, and coherent neighborhood development.</div>
                        <div class=" text-xl text-yellow-500">6. Support Local Worker Housing and Employer Partnerships</div>
                        <div class="ml-6">Collaborating with major employers to expand workforce housing, employer-assisted housing programs, and commuting alternatives can help reduce traffic and improve local affordability. This aligns housing supply with job growth.</div>
                        <div class="ml-6"><span class="underline">Rationale:</span> About 90% of people working in Verona commute from outside the city. This creates congestion and suggests local workers struggle to find suitable or affordable homes. Supporting worker housing improves economic stability and reduces travel demand.</div>
                        <div class=" text-xl text-yellow-500">7. Improve Transportation Safety and Reduce Congestion</div>
                        <div class="ml-6">Addressing high-crash intersections, improving signal timing, adding roundabouts where appropriate, and strengthening pedestrian safety infrastructure will help reduce congestion and accidents. These improvements make roads safer for all users.</div>
                        <div class="ml-6"><span class="underline">Rationale:</span> Traffic and crash data show clear problem areas in the transportation network. Safety-focused engineering solutions are cost-effective and enhance mobility. Reducing congestion supports quality of life and economic activity.</div>
                        <div class=" text-xl text-yellow-500">8. Promote Economic Diversification and Support Small Businesses</div>
                        <div class="ml-6">Encouraging a mix of industries—tech, retail, services, light industry—and supporting small businesses will help Verona maintain a resilient economy. Programs such as small business grants, façade improvements, and downtown entrepreneurship support can strengthen the local business community.</div>
                        <div class="ml-6"><span class="underline">Rationale:</span> Verona’s economy is growing, especially in tech and services. Diversifying employment opportunities helps stabilize the economy and supports the needs of residents across income levels. A vibrant local business ecosystem also boosts downtown vitality.</div>
                        <div class=" text-xl text-yellow-500">9. Strengthen Intergovernmental Coordination (Especially with the Town of Verona)</div>
                        <div class="ml-6">As the city grows outward, coordinated planning with adjacent jurisdictions becomes increasingly important. Shared service planning, utility coordination, and joint environmental protection efforts reduce duplication and create efficient long-term outcomes.</div>
                        <div class="ml-6"><span class="underline">Rationale:</span> Land near city edges is central to Verona’s future growth, and infrastructure systems cross jurisdictional boundaries. Coordination avoids conflicting development patterns and ensures environmental and infrastructure systems function effectively.</div>
                        <div class=" text-xl text-yellow-500">10. Maintain Ongoing and Inclusive Community Engagement</div>
                        <div class="ml-6">Verona should continue using online tools, pop-up events, youth outreach, and accessible engagement processes throughout plan implementation—not only during plan creation. Keeping dialogue active supports long-term success and community trust.</div>
                        <div class="ml-6"><span class="underline">Rationale:</span> The Envision Verona process demonstrates that residents are eager to participate and have provided valuable guidance. Continued engagement ensures the plan adapts over time and reflects evolving community needs and priorities.</div>
                    </div>
                </div>

                <hr/>

                <div>
                    <h4 class="text-xl font-bold mb-3">Summary</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <x-report.ul-with-header>
                            <li>Part 1: Zoning Code Evaluation Checklist — Six topics (Dimensional Requirements; Density; Land Use; Parking; Approval Processes; Non-Zoning Guidelines) to help communities spot regulatory barriers.</li>
                            <li>Part 2: Model Zoning Districts, Definitions, and Guidelines — Provides model district types (from small-lot single-family to medium-/high-density multi-family), recommended permitted land uses, accessory dwelling unit (ADU) standards, design guidance, and parking standards.</li>
                            <li>Part 3: Guide to Streamlining Housing Approvals — Recommendations to make the development process more efficient: e.g., reducing reliance on negotiated PUDs (Planned Unit Developments), enabling third-party review, using a development review team, and simplifying/providing clarity in approval steps.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-3">About Envision Verona</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <x-report.ul-with-header>
                            <li>Envision Verona is a multi-year update of Verona’s comprehensive plan (originally adopted 2009, reconfirmed 2019) — required under state rules to guide long-term growth, development, and land use.</li>
                            <li>The planning process is managed by a Task Force plus consultant support from MKSK (with others), and includes a formal public participation plan to ensure broad and inclusive input.</li>
                            <li>The participation strategy includes a variety of methods: open houses, pop-up events, online surveys/web-map, stakeholder interviews, and a public comment period.</li>
                            <li>The plan will address, per Wisconsin statute, nine major components: housing; issues & opportunities; transportation; utilities & community facilities; natural/cultural resources; economic development; intergovernmental cooperation; land use; and implementation.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-3">State of Community</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <x-report.ul-with-header>
                            <li>Population growth and demographic shift: Verona’s population has grown significantly — from around 10,600 in 2010 to ~14,000 by 2020 (≈32% increase).</li>
                            <li>Projections suggest continued growth: many regional and state planning agencies forecast 2–4% annual growth for Verona through 2030–2050.</li>
                            <li>The population has become both “older and younger”: growth in both youth (ages ~5–14) and older adult (60–74) age groups.</li>
                            <li>Household composition is changing: fewer married households, more single/unmarried households, and a decrease in average household size.</li>
                            <li>Median income has risen — for 2020, Verona’s median household income ($96,000) remains significantly higher than the surrounding region (Dane County average).</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-3">Housing & Affordability</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <x-report.ul-with-header>
                            <li>Over the last 15 years (2010–2024), Verona permitted roughly 2,250 new housing units — a substantial increase to meet demand.</li>
                            <li>Housing types added included many small (1-bedroom) units as well as larger 4+ bedroom houses. Detached single-family homes accounted for ~28% of new units; but almost half of all new units were in multi-unit buildings (10+ units).</li>
                            <li>As a result, nearly half of new residents are renters; renter share increased over the decade.</li>
                            <li>Rent burden increased: the share of renters spending 30% or more of their income on rent rose notably.</li>
                            <li>Home prices increased from a median of $250,000 in 2010 to about $320,000 in 2020 — a 25% jump, outpacing income growth.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-3">Land Use</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <x-report.ul-with-header>
                            <li>Verona remains a mix of residential and open space: residential (single-family, multi-family, duplex, senior), recreation/outdoor space, and agriculture (especially in surrounding Town-area) dominate current land use.</li>
                            <li>The area is served by an extensive trail/green-space network: regional trails (e.g., a major scenic trail), parks, wetlands, and natural areas. There are 25 parks, more than 160 acres of open space, and various conservancy/special use areas.</li>
                            <li>However: there are gaps in trail and park-access from many neighborhoods — despite being a regional center for trails and parks, accessibility remains uneven.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-3">Transportation & Mobility</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <x-report.ul-with-header>
                            <li>With growth came transportation pressures: increased traffic congestion, especially along major corridors (e.g., “Verona Ave” / US-18 ramps), rush-hour backups, and crash hotspots at certain intersections.</li>
                            <li>Public transit exists (bus routes), and there are trails/bike lanes — but mixed commuting patterns and high dependence on vehicles remain.</li>
                            <li>Despite growth and many jobs in Verona, 90% of workers employed in Verona jobs live outside the city — creating significant commuter inflow.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-3">Economy & Jobs</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <x-report.ul-with-header>
                            <li>Employment in Verona has doubled since 2009, largely driven by growth of businesses in “Information” (tech, professional) sectors — tied to local employers.</li>
                            <li>Other sectors have also grown: retail, food/accommodation, business-support services, manufacturing, etc.</li>
                            <li>The job base has diversified — offering opportunities for both high-education (tech/professional) and lower-barrier jobs (services, manufacturing), which may help economic inclusivity.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>

                <div>
                    <h4 class="text-xl font-bold mb-3">Community Feedback</h4>
                    <div class="ml-6 flex flex-col gap-4">
                        <x-report.ul-with-header>
                            <li>Strong interest in parks, recreation, open space, and green / natural resources — many want more access to nature, trees, preservation of natural areas.</li>
                            <li>Desire for a vibrant, active downtown — people value walkability, downtown character, and mixed-use development.</li>
                            <li>Need for diverse and affordable housing types — ranging from starter homes to multi-family units, senior housing, duplexes/townhomes, and housing suitable for different household types (singles, families, multigenerational).</li>
                            <li>Importance of transportation choices: many respondents prioritized being able to walk or bike for errands, recreation or commuting — not only driving.</li>
                            <li>Maintaining small-town / city character: even as Verona grows, many people expressed that keeping its character, natural charm, and community feel is important.</li>
                        </x-report.ul-with-header>
                    </div>
                </div>
            </div>

        </div>

    </section>

</x-layouts.app>
