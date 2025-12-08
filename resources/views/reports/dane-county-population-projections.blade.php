<x-layouts.app>

    <x-report.basic :report="$report">
        <x-slot:recommendations>
            <x-report.recommendations>
                <div class=" text-4xl text-yellow-500">Strong signal that planning matters now!</div>
                <x-report.ul-with-header title="Rationale:">
                    <li><span class="text-yellow-500">Substantial growth pressure:</span> Growing to 30,000 means Verona would need significant expansion in housing, infrastructure (roads, utilities), public services (schools, policing, municipal services), and amenities (parks, transit, commercial services).</li>
                    <li><span class="text-yellow-500">Housing demand will surge:</span> If Verona mirrors the county trend, many of the new residents will need housing — including rentals, starter homes, workforce housing, family homes, and possibly senior housing depending on demographic shifts. This reinforces the urgency of building new units, including affordable/workforce housing.</li>
                    <li><span class="text-yellow-500">Potential transition from small city/suburb to mid-sized city:</span> A population of 30,000 would change Verona’s character: perhaps more density, mixed housing types (townhouses, duplexes, apartments), more commercial development, and possibly evolving commuting patterns — especially if many residents work in Madison or other parts of Dane County.</li>
                    <li><span class="text-yellow-500">Need for proactive planning:</span> To handle such growth well, Verona (and Dane County planners) would need to coordinate — ensure zoning and land use support diverse housing types, that infrastructure expands sustainably, that open space and environmental resources aren’t overwhelmed, and that services (fire, schools, transit) scale in time.</li>
                </x-report.ul-with-header>
            </x-report.recommendations>
        </x-slot:recommendations>

        <x-slot:summary>
            <x-report.summary title="Dane County Summary">
                <x-report.ul-with-header title="Key Insights:">
                    <li>The RDG forecasts that Dane County will grow from ~561,500 residents in 2020 (per the U.S. Census) to ~887,000 residents by 2050. That’s an increase of ~325,000 people — a 58% rise over 30 years.</li>
                    <li>To accommodate that growth, the county will need roughly 160,000 new housing units — and that does not include additional units needed to catch up from past underproduction (i.e., backlog), or to restore healthy vacancy rates, or to moderate upward pressure on home prices.</li>
                    <li>The 2050 estimate (887,000) is a “median” derived from multiple trend-based projections (demography, housing, jobs), not just a simple linear extrapolation. The RDG argues that historical data show exponential growth, not linear — especially in population, jobs, and households.</li>
                    <li>This projection implies an average growth rate of ~16.5% per decade from 2020 to 2050 — substantially higher than the more conservative projections from the state-level Wisconsin Department of Administration (DOA).</li>
                    <li>The growth is expected to be driven by several factors: robust job growth (particularly in stable, high-skill sectors — healthcare, education, research, IT/professional services), continued inflow from students (especially from University of Wisconsin–Madison), attractive amenities (natural setting, transit and biking infrastructure, cultural institutions), and shifting housing-development policies in response to the county’s housing shortage.</li>
                    <li>The RDG also updated projections at the municipal level, not just countywide — meaning the 887,000 target is distributed among cities, towns, and villages based on plausible growth patterns (land availability, recent development trends, transportation access, local zoning, etc.).</li>
                </x-report.ul-with-header>
            </x-report.summary>

            <x-report.summary title="Verona, Wisconsin Summary">
                <x-report.ul-with-header title="Key Insights:">
                    <li>In 2000, Verona had ~7,052 residents; in 2010, ~10,619; in 2020, ~14,030.</li>
                    <li>Under RDG’s 2050 projection, Verona’s population is forecast to reach ~30,000 residents.</li>
                    <li>That would be an increase of ~16,000 people from 2020 — roughly a 115% increase (i.e., more than doubling the 2000 population, and more than doubling the 2020 population over three decades).</li>
                </x-report.ul-with-header>
            </x-report.summary>
        </x-slot:summary>

        <div>
            <h4 class="text-xl font-bold mb-3">Growth Factors</h4>
            <div class="ml-6 flex flex-col gap-4">
                <x-report.ul-with-header>
                    <li><span class="text-yellow-500">Job growth in stable industries</span>, including healthcare, education, advanced manufacturing, biomedical research, IT and professional and financial services, supported by the continual influx of UW students.</li>
                    <li><span class="text-yellow-500">UW-Madison</span> attracts students from around the nation and world, some of whom settle here; UW Madison ranked 6th in research in U.S., attracting investment in industries supported by research.</li>
                    <li><span class="text-yellow-500">Exceptional healthcare</span> services with five major hospitals and over a dozen clinics forming a regional healthcare hub.</li>
                    <li><span class="text-yellow-500">Urban and rural amenities</span> including walking, biking and transit infrastructure; cultural institutions; an attractive natural setting; and world-class agriculture.</li>
                </x-report.ul-with-header>
            </div>
        </div>

        <div>
            <h4 class="text-xl font-bold mb-3">Caveats / Uncertainty</h4>
            <div class="ml-6 flex flex-col gap-4">
                <x-report.ul-with-header>
                    <li>The 2050 Verona projection (30,000) comes from allocating county-wide growth based on assumptions: land availability, recent development, and municipal trends. RDG explicitly notes that projections are rounded, and there is uncertainty.</li>
                    <li>If future economic, demographic, or policy trends change (e.g., slower migration, changes in household size, economic downturns, stricter growth controls), actual population could end up lower (or potentially higher) than projected. RDG plans to revisit/update every five years.</li>
                    <li>Growth at county level does not guarantee uniform growth across all municipalities — some areas may grow more, some less. For Verona, the 30,000 is a projection; actual growth will depend on many local factors (zoning, housing development approvals, demand, commuting patterns, etc.).</li>
                    <li>Infrastructure, services, and environmental or land-use constraints may impose de facto limits on growth even if population demand exists.</li>
                </x-report.ul-with-header>
            </div>
        </div>

    </x-report.basic>

</x-layouts.app>
