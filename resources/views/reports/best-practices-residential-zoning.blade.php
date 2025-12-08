<x-layouts.app>

    <x-report.basic :report="$report">

        <x-slot:recommendations>
            <x-report.recommendations>
                <div class=" text-xl text-yellow-500">1. Expand permitted housing types beyond traditional single-family homes.</div>
                <div class="ml-6">For example: allow duplexes, townhomes, small multi-unit buildings, cottage-cluster developments, and accessory dwelling units (ADUs).</div>
                <div class="ml-6"><span class="underline">Rationale:</span> This aligns with the Guide’s core principle: a variety of housing types helps meet changing household needs (smaller households, varied incomes, renters vs owners), and increases total housing supply.</div>
                <div class=" text-xl text-yellow-500">2. Introduce zoning districts or overlay zones based on the model district types from the Guide.</div>
                <div class="ml-6">E.g., a “Mixed-Middle Residential” or “Medium Multi-Family Residential” district where density, lot size, and land-use mix are calibrated for more compact housing.</div>
                <div class="ml-6"><span class="underline">Rationale:</span> Provides a clear framework for where higher-density and “missing middle” housing is appropriate — reducing ad-hoc rezoning or protests, and giving developers/policy-makers clarity. The Guide provides sample language and definitions to help.</div>
                <div class=" text-xl text-yellow-500">3. Reduce minimum lot sizes, lot widths, setbacks, and lot-depth requirements (especially in areas targeted for higher density or near amenities/transport).</div>
                <div class="ml-6">Consider allowing lot sizes down to ~5,000–7,500 ft² (or even smaller in some contexts), narrow lot widths (30–60 ft), zero-lot-line/townhome-style development, smaller front setbacks (10–15 ft), and minimal side/rear setbacks especially for alley-loaded units.</div>
                <div class="ml-6"><span class="underline">Rationale:</span> Lowering these dimensional requirements reduces per-unit land and infrastructure costs; makes smaller, more affordable homes viable; and promotes more compact, efficient neighborhoods.</div>
                <div class=" text-xl text-yellow-500">4. Eliminate or relax minimum dwelling unit size requirements.</div>
                <div class="ml-6">Allow small-format housing (e.g., units under 1,000 ft²), perhaps aimed at first-time homebuyers, singles, or seniors.</div>
                <div class="ml-6"><span class="underline">Rationale:</span> Smaller units generally cost less to build and maintain, broadening affordability and offering diverse housing options — consistent with changing demographics and household sizes.</div>
                <div class=" text-xl text-yellow-500">5. Reduce parking requirements (or provide flexible parking standards).</div>
                <div class="ml-6">Especially for denser or multi-unit developments, and in areas near walkable amenities or transit.</div>
                <div class="ml-6"><span class="underline">Rationale:</span> Lower parking requirements reduce land use and building costs, making housing more affordable and enabling more efficient, compact development.</div>
                <div class=" text-xl text-yellow-500">6. Allow multi-unit (e.g. duplex, triplex, small apartment) housing “by right” in appropriate zones.</div>
                <div class="ml-6">Rather than only via conditional use, rezoning, or special-use permits.</div>
                <div class="ml-6"><span class="underline">Rationale:</span> This reduces regulatory uncertainty and delays, lowering the risk and cost for developers — which can unlock more housing supply.</div>
                <div class=" text-xl text-yellow-500">7. Streamline the development approval process.</div>
                <div class="ml-6">Reduce the overuse of PUDs, create a standing development-review team, allow third-party review, and simplify application procedures for “by-right” housing.</div>
                <div class="ml-6"><span class="underline">Rationale:</span> Accelerating approvals and reducing complexity lowers soft costs (time, administrative burden), and makes affordable housing projects more feasible.</div>
                <div class=" text-xl text-yellow-500">8. Engage in community discussion and stakeholder outreach (residents, planners, developers, local officials).</div>
                <div class="ml-6">Before rewriting zoning codes — using the Guide as a starting reference.</div>
                <div class="ml-6"><span class="underline">Rationale:</span> Because the Guide is not “one size fits all,” tailoring zoning reform to Verona’s character, growth patterns, local infrastructure, and community values will help balance housing needs and neighborhood preservation.</div>
            </x-report.recommendations>
        </x-slot:recommendations>

        <x-slot:summary>
            <x-report.summary>
                <x-report.ul-with-header>
                    <li>Part 1: Zoning Code Evaluation Checklist — Six topics (Dimensional Requirements; Density; Land Use; Parking; Approval Processes; Non-Zoning Guidelines) to help communities spot regulatory barriers.</li>
                    <li>Part 2: Model Zoning Districts, Definitions, and Guidelines — Provides model district types (from small-lot single-family to medium-/high-density multi-family), recommended permitted land uses, accessory dwelling unit (ADU) standards, design guidance, and parking standards.</li>
                    <li>Part 3: Guide to Streamlining Housing Approvals — Recommendations to make the development process more efficient: e.g., reducing reliance on negotiated PUDs (Planned Unit Developments), enabling third-party review, using a development review team, and simplifying/providing clarity in approval steps.</li>
                </x-report.ul-with-header>
            </x-report.summary>
        </x-slot:summary>

        <div>
            <h4 class="text-xl font-bold mb-3">Key Principles & Recommended Zoning Adjustments</h4>
            <div class="ml-6 flex flex-col gap-4">
                <x-report.ul-with-header>
                    <li><span class="font-bold">Allow a wide variety of housing types</span> — Not just large single-family homes, but also smaller lots, duplexes/townhomes, multi-unit buildings, accessory dwelling units, cottage clusters, and other “middle-housing” forms.</li>
                    <li><span class="font-bold">Reduce minimum lot sizes and lot widths</span> — For example, allowing lots smaller than 10,000 ft²; encouraging 5,000–7,500 ft² street-loaded lots; and even 3,000 ft² for alley-loaded lots.</li>
                    <li><span class="font-bold">Permit “zero-lot line” development (townhomes/duplexes) with fee-simple ownership</span> — so more households can own rather than rent, while keeping development compact.</li>
                    <li><span class="font-bold">Reduce large setbacks, lot depth requirements, and building-separation standards</span> — Aligning more with older/“traditional” neighborhood patterns rather than sprawling suburban models; for example, allowing 10–15 ft front setbacks in urban/suburban settings and minimal side/rear setbacks for alley-loaded units.</li>
                    <li><span class="font-bold">Allow smaller dwelling units, and eliminate (or reduce) minimum floor-area requirements</span> — to support smaller households and make housing more affordable (e.g., units under 1,000 ft²).</li>
                    <li><span class="font-bold">Right-to-build for multi-unit housing (i.e., allow multi-family buildings by right, not only by conditional use or special rezoning)</span> — which reduces uncertainty for developers and speeds up housing production.</li>
                    <li><span class="font-bold">Rethink parking requirements downward</span> — less parking per unit (or shared parking), especially near transit or in more compact neighborhoods, to reduce land and construction costs.</li>
                    <li><span class="font-bold">Streamline approval processes</span> — reduce regulatory hurdles, complexity, and delays that increase development cost. This includes simplifying review, limiting overuse of PUDs, enabling third-party reviews, and establishing clear process steps.</li>
                </x-report.ul-with-header>
            </div>
        </div>

    </x-report.basic>

</x-layouts.app>
