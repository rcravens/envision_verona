<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create( 'housing_prices', function ( Blueprint $table ) {
            $table->id();

            $table->string( 'postal_code' );
            $table->dateTime( 'date' );
            $table->string( 'property_type' )->nullable();
            $table->integer( 'bedrooms' )->nullable();
            $table->float( 'average_price' );
            $table->float( 'median_price' );
            $table->float( 'min_price' );
            $table->float( 'max_price' );
            $table->float( 'average_price_per_square_foot' )->nullable();
            $table->float( 'median_price_per_square_foot' )->nullable();
            $table->float( 'min_price_per_square_foot' )->nullable();
            $table->float( 'max_price_per_square_foot' )->nullable();
            $table->float( 'average_square_footage' )->nullable();
            $table->float( 'median_square_footage' )->nullable();
            $table->float( 'min_square_footage' )->nullable();
            $table->float( 'max_square_footage' )->nullable();
            $table->float( 'average_days_on_market' )->nullable();
            $table->float( 'median_days_on_market' )->nullable();
            $table->float( 'min_days_on_market' )->nullable();
            $table->float( 'max_days_on_market' )->nullable();
            $table->integer( 'new_listings_count' );
            $table->integer( 'total_listings_count' );

            $table->timestamps();
        } );

        Schema::create( 'rental_prices', function ( Blueprint $table ) {
            $table->id();

            $table->string( 'postal_code' );
            $table->dateTime( 'date' );
            $table->string( 'property_type' )->nullable();
            $table->integer( 'bedrooms' )->nullable();
            $table->float( 'average_rent' );
            $table->float( 'median_rent' );
            $table->float( 'min_rent' );
            $table->float( 'max_rent' );
            $table->float( 'average_rent_per_square_foot' )->nullable();
            $table->float( 'median_rent_per_square_foot' )->nullable();
            $table->float( 'min_rent_per_square_foot' )->nullable();
            $table->float( 'max_rent_per_square_foot' )->nullable();
            $table->float( 'average_square_footage' )->nullable();
            $table->float( 'median_square_footage' )->nullable();
            $table->float( 'min_square_footage' )->nullable();
            $table->float( 'max_square_footage' )->nullable();
            $table->float( 'average_days_on_market' )->nullable();
            $table->float( 'median_days_on_market' )->nullable();
            $table->float( 'min_days_on_market' )->nullable();
            $table->float( 'max_days_on_market' )->nullable();
            $table->integer( 'new_listings_count' );
            $table->integer( 'total_listings_count' );

            $table->timestamps();
        } );
    }

    public function down(): void
    {
        Schema::dropIfExists( 'rental_prices' );
        Schema::dropIfExists( 'housing_prices' );
    }
};
