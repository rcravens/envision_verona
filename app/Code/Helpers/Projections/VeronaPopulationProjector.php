<?php

class VeronaPopulationProjector
{
    // Historical population data (1960–2024)
    private $historical_data = [
        1960 => 1471,
        1970 => 2334,
        1980 => 3336,
        1990 => 5374,
        2000 => 7052,
        2010 => 10619,
        2020 => 14030,
        2024 => 16825
    ];

    // Model parameters

    // Maximum annual growth rate for the logistic component, representing
    // intrinsic population growth (excluding Epic-driven migration and natural
    // increase) when the population is far below the carrying capacity. Based
    // on Verona’s historical growth of ~3–6% post-2000, driven by regional
    // economic trends and non-Epic migration. Default: 0.06 (6%).
    private float $r_max;

    // Initial population limit Verona can sustain without new development,
    // based on current land use, housing stock, and infrastructure (e.g., schools, Hwy M).
    // Estimated from Verona’s 2024 comprehensive plan and Dane County’s growth projections,
    // allowing ~2–3x current population (16,825 in 2024). Default: 40000.
    private int $carrying_capacity;

    // Annual increase in carrying capacity (people/year) to reflect new development
    // opportunities, such as housing expansions or infrastructure improvements
    // (e.g., $182M school referendum, new subdivisions). Set to 500 in the optimistic
    // scenario to reach ~50,000 by 2045; 0 for baseline/pessimistic to assume no major
    // new development. Default: 0.
    private float $carrying_capacity_increase_rate;

    // Number of new residents (employees + family) per net new Epic job, driving
    // migration-based growth. Based on ACS data (Verona’s household size ~2.5),
    // adjusted for Epic’s young professional workforce, yielding ~1 employee + 0.8
    // family members. Default: 1.8.
    private float $multiplier;

    // Scaling factor for migration, reflecting housing availability and affordability.
    // A value of 1.0 assumes housing keeps pace with demand (per Verona’s 2024
    // housing plans); <1 reduces migration if housing lags; >1 reflects incentives.
    // Default: 1.0 (no constraints).
    private float $housing_factor;

    // Annual population growth rate due to births minus deaths, reflecting Verona’s
    // young, family-oriented demographics (median age 38.3, 22.7% under 15).
    // Based on ACS data (~12 births, ~6 deaths per 1,000), yielding a net rate of ~0.3%.
    // Default: 0.003 (0.3%).
    private float $natural_increase_rate;

    // Type of Epic Systems job growth model:
    //  'percentage' (baseline, linear growth from 10% to 5%),
    //  'fixed' (optimistic, constant jobs/year), or
    //  'plateau' (pessimistic, caps at 15,000 jobs).
    // Default: 'percentage' to reflect historical hiring trends and expected tapering.
    // Default: 'percentage'.
    private string $epic_growth_type;

    // Annual percentage growth rate of Epic jobs in 2025 for the percentage-based
    // model, based on Epic’s 2023–2024 hiring (~1,700 jobs, ~13% growth). Reflects
    // near-term aggressive expansion (e.g., new campus, AI initiatives).
    // Default: 0.10 (10%).
    private float $epic_growth_start;

    // Annual percentage growth rate of Epic jobs in 2045 for the percentage-based model,
    // assuming slower growth due to market saturation or AI efficiencies, but sustained
    // by global EHR market growth (~8–10%). Default: 0.05 (5%).
    private float $epic_growth_end;

    // Gross number of new Epic jobs added annually (before turnover) in the optimistic
    // scenario’s fixed model, reflecting sustained hiring (~1,000 jobs/year) to
    // reach ~33,000 jobs by 2045. Default: 0 (unused in baseline percentage model).
    private int $epic_fixed_jobs_per_year;

    // Maximum number of Epic jobs in the pessimistic scenario’s plateau model, after
    // which growth stops except for replacement hiring. Set to 15,000 to reflect a
    // plateau by ~2028, based on potential AI efficiencies or economic headwinds.
    // Default: 0 (unused in baseline).
    private int $epic_plateau_jobs;

    // Year when Epic jobs reach the plateau threshold in the pessimistic scenario,
    // triggering minimal replacement hiring. Set to 2028, assuming ~10% growth
    // from 14,700 jobs in 2024. Default: 2028.
    private int $epic_plateau_year;

    // Gross number of new Epic jobs per year (before turnover) in the pessimistic
    // scenario after reaching the plateau, to replace turnover and maintain ~15,000 jobs.
    // Reflects minimal growth (~100 jobs/year). Default: 100.
    private int $epic_replacement_jobs;

    // Year from which projections begin, using 2024 data (population 16,825,
    // Epic jobs ~14,700) as the starting point. Aligns with the provided scenarios
    // and ensures continuity from historical data. Default: 2025.
    private int $base_year;

    // Fraction of Epic jobs lost annually due to turnover, reducing gross job growth
    // to net growth (e.g., 1,000 gross jobs → 800 net at 20%). Based on Glassdoor/Reddit
    // insights (~20–30% turnover), set conservatively to reflect Epic’s high-demand
    // tech environment. Default: 0.20 (20%).
    private float $turnover_rate;


    public function __construct( array $params = [] )
    {
        $this->r_max                           = $params[ 'r_max' ] ?? 0.02;
        $this->carrying_capacity               = $params[ 'carrying_capacity' ] ?? 18000;
        $this->carrying_capacity_increase_rate = $params[ 'carrying_capacity_increase_rate' ] ?? 500;
        $this->multiplier                      = $params[ 'multiplier' ] ?? 1.8;
        $this->housing_factor                  = $params[ 'housing_factor' ] ?? 1.0;
        $this->natural_increase_rate           = $params[ 'natural_increase_rate' ] ?? 0.003;
        $this->epic_growth_type                = $params[ 'epic_growth_type' ] ?? 'percentage';
        $this->epic_growth_start               = $params[ 'epic_growth_start' ] ?? 0.10;
        $this->epic_growth_end                 = $params[ 'epic_growth_end' ] ?? 0.05;
        $this->epic_fixed_jobs_per_year        = $params[ 'epic_fixed_jobs_per_year' ] ?? 0;
        $this->epic_plateau_jobs               = $params[ 'epic_plateau_jobs' ] ?? 0;
        $this->epic_plateau_year               = $params[ 'epic_plateau_year' ] ?? 2028;
        $this->epic_replacement_jobs           = $params[ 'epic_replacement_jobs' ] ?? 100;
        $this->base_year                       = $params[ 'base_year' ] ?? 2025;
        $this->turnover_rate                   = $params[ 'turnover_rate' ] ?? 0.20;
    }

    public function calculateNextPopulation( float $current_population, int $year, int $current_epic_jobs ): array
    {
        $carrying_capacity = $this->calculateCarryingCapacity( $year );

        $migration_rate = $this->calculateMigrationRate( $year, $current_population, $current_epic_jobs );

        $total_growth_rate = ( $this->r_max + $migration_rate + $this->natural_increase_rate ) *
                             ( 1 - $current_population / $carrying_capacity );

        // Calculate next population and Epic jobs
        $next_population = $current_population * ( 1 + $total_growth_rate );
        $next_epic_jobs  = $this->calculateEpicJobs( $year, $current_epic_jobs );

        return [
            round( $next_population ),
            $next_epic_jobs
        ];
    }

    public function getPopulation( int $year ): ?array
    {
        if ( isset( $this->historical_data[ $year ] ) )
        {
            $epic_jobs = $year >= 2000 ? min( 14700 + ( $year - 2024 ) * 1000, 15000 ) : 0;

            return [
                (float) $this->historical_data[ $year ],
                $epic_jobs
            ];
        }

        if ( $year < min( array_keys( $this->historical_data ) ) || $year > 2045 )
        {
            return null;
        }

        $current_year       = 2024;
        $current_population = $this->historical_data[ 2024 ];
        $current_epic_jobs  = 14700;

        while( $current_year < $year )
        {
            [
                $current_population,
                $current_epic_jobs
            ] = $this->calculateNextPopulation(
                $current_population,
                $current_year + 1,
                $current_epic_jobs
            );
            $current_year ++;
        }

        return [
            $current_population,
            $current_epic_jobs
        ];
    }

    public function getPopulationRange( int $start_year, int $end_year, int $interval = 1 ): array
    {
        $projections = [];
        for ( $year = $start_year; $year <= $end_year; $year += $interval )
        {
            $result = $this->getPopulation( $year );
            if ( $result !== null )
            {
                $projections[ $year ] = [
                    'population'        => $result[ 0 ],
                    'epic_jobs'         => $result[ 1 ],
                    'carrying_capacity' => $this->calculateCarryingCapacity( $year )
                ];
            }
        }

        return $projections;
    }

    public function getHistoricalData(): array
    {
        return $this->historical_data;
    }

    public function getParameters(): array
    {
        return [
            'r_max'                           => $this->r_max,
            'carrying_capacity'               => $this->carrying_capacity,
            'carrying_capacity_increase_rate' => $this->carrying_capacity_increase_rate,
            'multiplier'                      => $this->multiplier,
            'housing_factor'                  => $this->housing_factor,
            'natural_increase_rate'           => $this->natural_increase_rate,
            'epic_growth_type'                => $this->epic_growth_type,
            'epic_growth_start'               => $this->epic_growth_start,
            'epic_growth_end'                 => $this->epic_growth_end,
            'epic_fixed_jobs_per_year'        => $this->epic_fixed_jobs_per_year,
            'epic_plateau_jobs'               => $this->epic_plateau_jobs,
            'epic_plateau_year'               => $this->epic_plateau_year,
            'epic_replacement_jobs'           => $this->epic_replacement_jobs,
            'base_year'                       => $this->base_year,
            'turnover_rate'                   => $this->turnover_rate,
        ];
    }

    private function calculateCarryingCapacity( int $year ): float
    {
        if ( $year < $this->base_year )
        {
            return $this->carrying_capacity;
        }

        return $this->carrying_capacity + $this->carrying_capacity_increase_rate * ( $year - $this->base_year );
    }

    private function calculateNetEpicJobs( int $year, int $current_epic_jobs ): int
    {
        if ( $this->epic_growth_type === 'fixed' )
        {
            return round( $this->epic_fixed_jobs_per_year * ( 1 - $this->turnover_rate ) );
        }
        elseif ( $this->epic_growth_type === 'plateau' && ( $year >= $this->epic_plateau_year || $current_epic_jobs >= $this->epic_plateau_jobs ) )
        {
            return round( $this->epic_replacement_jobs * ( 1 - $this->turnover_rate ) );
        }
        else
        {
            $growth_rate = $this->calculateEpicGrowthRate( $year );

            return round( $current_epic_jobs * $growth_rate * ( 1 - $this->turnover_rate ) );
        }
    }

    private function calculateEpicGrowthRate( int $year ): float
    {
        if ( $this->epic_growth_type !== 'percentage' )
        {
            return 0;
        }
        if ( $year <= $this->base_year )
        {
            return $this->epic_growth_start;
        }
        if ( $year >= 2045 )
        {
            return $this->epic_growth_end;
        }
        $fraction = ( $year - $this->base_year ) / ( 2045 - $this->base_year );

        return $this->epic_growth_start - $fraction * ( $this->epic_growth_start - $this->epic_growth_end );
    }

    private function calculateMigrationRate( int $year, float $current_population, int $current_epic_jobs ): float
    {
        $net_jobs = $this->calculateNetEpicJobs( $year, $current_epic_jobs );

        return ( $net_jobs * $this->multiplier * $this->housing_factor ) / $current_population;
    }

    private function calculateEpicJobs( int $year, int $current_epic_jobs ): int
    {
        $net_jobs = $this->calculateNetEpicJobs( $year, $current_epic_jobs );

        return $current_epic_jobs + $net_jobs;
    }
}


// Baseline Model
$baseline_params      = [
    'epic_growth_type'                => 'percentage',
    'epic_growth_start'               => 0.10,
    'epic_growth_end'                 => 0.05,
    'multiplier'                      => 1.8,
    'turnover_rate'                   => 0.20,
    'carrying_capacity_increase_rate' => 250,
    // 18000 + 20*250 = 22500 in 2045
];
$baseline_projector   = new VeronaPopulationProjector( $baseline_params );
$baseline_projections = $baseline_projector->getPopulationRange( 2025, 2045, 5 );

// Optimistic Model
$optimistic_params      = [
    'epic_growth_type'                => 'fixed',
    'epic_fixed_jobs_per_year'        => 1000,
    'multiplier'                      => 1.8,
    'turnover_rate'                   => 0.20,
    'carrying_capacity_increase_rate' => 500,
    // 18000 + 20*500 = 28000 in 2045
];
$optimistic_projector   = new VeronaPopulationProjector( $optimistic_params );
$optimistic_projections = $optimistic_projector->getPopulationRange( 2025, 2045, 5 );

// Pessimistic Model
$pessimistic_params      = [
    'epic_growth_type'                => 'plateau',
    'epic_plateau_jobs'               => 15000,
    'epic_plateau_year'               => 2028,
    'epic_replacement_jobs'           => 100,
    'multiplier'                      => 1.8,
    'turnover_rate'                   => 0.20,
    'carrying_capacity_increase_rate' => 0,
    // 18000 + 20*0 = 18000 in 2045
];
$pessimistic_projector   = new VeronaPopulationProjector( $pessimistic_params );
$pessimistic_projections = $pessimistic_projector->getPopulationRange( 2025, 2045, 5 );

// Output results
echo "Year | Baseline | Optimistic | Pessimistic | Baseline K | Optimistic K | Pessimistic K | Baseline Jobs | Optimistic Jobs | Pessimistic Jobs\n";
echo "-----|----------|------------|-------------|------------|--------------|---------------|---------------|-----------------|-----------------\n";
foreach (
    [
        2025,
        2030,
        2035,
        2040,
        2045
    ] as $year
)
{
    $baseline         = $baseline_projections[ $year ][ 'population' ] ?? 'N/A';
    $optimistic       = $optimistic_projections[ $year ][ 'population' ] ?? 'N/A';
    $pessimistic      = $pessimistic_projections[ $year ][ 'population' ] ?? 'N/A';
    $baseline_k       = $baseline_projections[ $year ][ 'carrying_capacity' ] ?? 'N/A';
    $optimistic_k     = $optimistic_projections[ $year ][ 'carrying_capacity' ] ?? 'N/A';
    $pessimistic_k    = $pessimistic_projections[ $year ][ 'carrying_capacity' ] ?? 'N/A';
    $baseline_jobs    = $baseline_projections[ $year ][ 'epic_jobs' ] ?? 'N/A';
    $optimistic_jobs  = $optimistic_projections[ $year ][ 'epic_jobs' ] ?? 'N/A';
    $pessimistic_jobs = $pessimistic_projections[ $year ][ 'epic_jobs' ] ?? 'N/A';
    echo "$year | $baseline | $optimistic | $pessimistic | $baseline_k | $optimistic_k | $pessimistic_k | $baseline_jobs | $optimistic_jobs | $pessimistic_jobs\n";
}
