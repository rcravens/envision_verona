<?php

namespace App\Models;

use Illuminate\Support\Str;
use stdClass;

class Reports
{
    private static ?self $instance = null;

    private array $reports;

    private function __construct()
    {
        $reports = [];

        $report                   = new \stdClass();
        $report->name             = 'Housing Affordability Analysis';
        $report->url              = 'https://www.veronawi.gov/DocumentCenter/View/5274/Housing-Affordability-Analysis---Report-Year-2024-Permit-Year-2022-and-2023';
        $report->year             = 2024;
        $report->slug             = Str::slug( $report->name );
        $reports[ $report->slug ] = $report;

        $report                   = new \stdClass();
        $report->name             = 'Dane County Regional Housing Strategy';
        $report->url              = 'https://rhs.danecounty.gov/';
        $report->year             = 2024;
        $report->slug             = Str::slug( $report->name );
        $reports[ $report->slug ] = $report;

        $report                   = new \stdClass();
        $report->name             = 'Envision Verona - Comprehensive Plan Project';
        $report->url              = 'https://engagemksk.mysocialpinpoint.com/verona-comprehensive-plan';
        $report->year             = 2024;
        $report->slug             = Str::slug( $report->name );
        $reports[ $report->slug ] = $report;

        $report                   = new \stdClass();
        $report->name             = 'Dane County Population Projections';
        $report->url              = 'https://rhs.danecounty.gov/documents/pdf/FactSheets/Fact-Sheet---RDG-2050-Population-Projections-for-Dane-County-2025-08-22.pdf';
        $report->year             = 2024;
        $report->slug             = Str::slug( $report->name );
        $reports[ $report->slug ] = $report;

        $report                   = new stdClass();
        $report->name             = 'Best Practices Residential Zoning';
        $report->url              = 'https://rhs.danecounty.gov/documents/pdf/Best-Practices-Residential-Zoning---11.5.25.pdf';
        $report->year             = 2024;
        $report->slug             = Str::slug( $report->name );
        $reports[ $report->slug ] = $report;

        $report                   = new stdClass();
        $report->name             = 'Affordable Housing 101';
        $report->url              = 'https://rhs.danecounty.gov/documents/pdf/FactSheets/RHS-Affordable-Housing-101-v3.pdf';
        $report->year             = 2024;
        $report->slug             = Str::slug( $report->name );
        $reports[ $report->slug ] = $report;

        ksort( $reports );

        $this->reports = $reports;
    }

    public static function instance(): self
    {
        if ( is_null( self::$instance ) )
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function all(): array
    {
        return $this->reports;
    }

    public function report( $slug ): ?\stdClass
    {
        return $this->reports[ $slug ] ?? null;
    }
}
