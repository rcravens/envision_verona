<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create( 'reports', function ( Blueprint $table ) {
            $table->id();
            $table->string( 'slug' )->unique();
            $table->string( 'title' );
            $table->text( 'description' )->nullable();
            $table->string( 'url' );
            $table->integer( 'year' )->nullable();
            $table->json( 'categories' )->nullable();
            $table->timestamps();
        } );

        $json    = '[{"id":1,"slug":"affordable-housing-101","title":"Affordable Housing 101","description":null,"url":"https:\/\/rhs.danecounty.gov\/documents\/pdf\/FactSheets\/RHS-Affordable-Housing-101-v3.pdf","year":2024,"categories":[]},{"id":2,"slug":"best-practices-residential-zoning","title":"Best Practices Residential Zoning","description":null,"url":"https:\/\/rhs.danecounty.gov\/documents\/pdf\/Best-Practices-Residential-Zoning---11.5.25.pdf","year":2024,"categories":[]},{"id":3,"slug":"dane-county-population-projections","title":"Dane County Population Projections","description":null,"url":"https:\/\/rhs.danecounty.gov\/documents\/pdf\/FactSheets\/Fact-Sheet---RDG-2050-Population-Projections-for-Dane-County-2025-08-22.pdf","year":2024,"categories":[]},{"id":4,"slug":"dane-county-regional-housing-strategy","title":"Dane County Regional Housing Strategy","description":null,"url":"https:\/\/rhs.danecounty.gov\/","year":2024,"categories":[]},{"id":5,"slug":"envision-verona-comprehensive-plan-project","title":"Envision Verona - Comprehensive Plan Project","description":null,"url":"https:\/\/engagemksk.mysocialpinpoint.com\/verona-comprehensive-plan","year":2024,"categories":[]},{"id":6,"slug":"housing-affordability-analysis","title":"Housing Affordability Analysis","description":null,"url":"https:\/\/www.veronawi.gov\/DocumentCenter\/View\/5274\/Housing-Affordability-Analysis---Report-Year-2024-Permit-Year-2022-and-2023","year":2024,"categories":[]}]';
        $reports = json_decode( $json );
        foreach ( $reports as $report )
        {
            $r             = new \App\Models\Report();
            $r->slug       = $report->slug;
            $r->title      = $report->title;
            $r->url        = $report->url;
            $r->year       = $report->year;
            $r->categories = [];
            $r->save();
        }
    }

    public function down(): void
    {
        Schema::dropIfExists( 'reports' );
    }
};
