<?php

namespace App\Code\Integrations;

use Mockery\Exception;

class RentCastApi
{
    /*
     * end-points:
     *  /properties                     - property data
     *  /avm/value                      - property value + comparable properties
     *  /avm/rent/long-term             - property rent estimate + comparable properties
     *  /listings                       - aggregate "for-sale" and "for-rent" listings
     *  /listings/sale                  - "for-sale" listings
     *  /listings/rental/long-term      - active & inactive "for-rent" listings
     *  /markets                        - aggregate sale & rental listing data
     */
    private string $base_url = 'https://api.rentcast.io/v1/';
    private string $api_key;

    public function __construct( string $api_key )
    {
        $this->api_key = $api_key;
    }

    public function properties( array $filters ): mixed
    {
        return $this->get( 'properties', $filters );
    }

    public function listings_houses( array $filters ): mixed
    {
        return $this->get( 'listings/sale', $filters );
    }

    public function listings_rentals( array $filters ): mixed
    {
        return $this->get( 'listings/rental/long-term', $filters );
    }

    public function property_values( array $filters ): mixed
    {
        return $this->get( 'avm/value', $filters );
    }

    public function property_rent_estimates( array $filters ): mixed
    {
        return $this->get( 'avm/value', $filters );
    }

    public function markets( array $filters ): mixed
    {
        return $this->get( 'markets', $filters );
    }

    public function getRentalVacancyRateByZip( string $zipCode, array $additionalFilters = [] ): float
    {
        // Step 1: count active listings
        $activeResult = $this->get( 'listings/rental/long-term', array_merge( $additionalFilters, [
            'zipCode' => $zipCode,
            'status'  => 'Active'
        ] ) );
        $activeCount  = $activeResult->total_count;

        // Step 2: count total listings (any status)
        $totalResult = $this->get( 'listings/rental/long-term', array_merge( $additionalFilters, [
            'zipCode' => $zipCode
            // no status filter
        ] ) );
        $totalCount  = $totalResult->total_count;

        if ( $totalCount === 0 )
        {
            return 0.0;
        }

        dd( $activeResult, $totalResult );

        return 100.0 * ( $activeCount / $totalCount );
    }

    private function get( string $end_point, array $params = [] ): mixed
    {
        $url = $this->base_url . ltrim( $end_point, '/' );

        $params[ 'includeTotalCount' ] = 'true';

        if ( ! empty( $params ) )
        {
            $url .= '?' . http_build_query( $params );
        }

        $ch               = curl_init( $url );
        $response_headers = [];

        curl_setopt_array( $ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADERFUNCTION => function ( $curl, $header ) use ( &$response_headers ) {
                $len          = strlen( $header );
                $header_parts = explode( ':', $header, 2 );
                if ( count( $header_parts ) === 2 )
                {
                    $name                      = strtolower( trim( $header_parts[ 0 ] ) );
                    $value                     = trim( $header_parts[ 1 ] );
                    $response_headers[ $name ] = $value;
                }

                return $len;
            },
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'X-Api-Key: ' . $this->api_key,
            ]
        ] );


        $response = curl_exec( $ch );
        $status   = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

        if ( curl_errno( $ch ) )
        {
            throw new Exception( 'cURL error: ' . curl_error( $ch ) );
        }

        curl_close( $ch );

        $data = json_decode( $response, true );

        if ( $status >= 400 )
        {
            $message = $data[ 'message' ] ?? 'Unknown API Error';
            throw new Exception( "AP Error ({$status}):, {$message}" );
        }

        $total_count = isset( $response_headers[ 'x-total-count' ] ) ? (int) $response_headers[ 'x-total-count' ] : null;

        return (object) [
            'total_count' => $total_count,
            'data'        => $data
        ];
    }
}
