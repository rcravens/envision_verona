<?php

namespace App\Http\Controllers;

use App\Code\Integrations\RentCastApi;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;
use RuntimeException;

class BobsController extends Controller
{
    public function test( Request $request ): View
    {
//        $result = $this->scrapeApartmentsByZip( '53593' );
//        dd( $result );


        $api_key  = config( 'services.rentcast.key' );
        $api      = new RentCastApi( $api_key );
        $response = $api->markets( [ 'zipCode' => '53593' ] );
        dd( 'here', $response );
    }

    private function getLocationInfo( string $zip ): array
    {
        // Simple lookup (expand with a DB/API for production)
        $locations = [
            '53593' => [ 'city' => 'verona', 'state' => 'wi' ],
            '10001' => [ 'city' => 'new-york', 'state' => 'ny' ],
            // Add more: e.g., '90210' => ['beverly-hills', 'ca']
        ];

        return $locations[ $zip ] ?? throw new InvalidArgumentException( "ZIP $zip not in lookup. Add to getLocationInfo()." );
    }

    private function ensureCACert( string $path = null ): string
    {
        $path = $path ?: __DIR__ . '/cacert.pem';
        if ( file_exists( $path ) )
        {
            return $path;
        }

        echo "Downloading fresh CA bundle…\n";
        $ch = curl_init( 'https://curl.se/ca/cacert.pem' );
        curl_setopt_array( $ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ] );
        $data = curl_exec( $ch );
        $code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        curl_close( $ch );

        if ( $code !== 200 || ! $data )
        {
            throw new RuntimeException( "Failed to download CA bundle (HTTP $code)" );
        }
        file_put_contents( $path, $data );

        return $path;
    }

    private function scrapeApartmentsByZip( string $zip, int $maxRetries = 2 ): array
    {
        $loc = $this->getLocationInfo( $zip );
        $url = sprintf( 'https://www.apartments.com/%s-%s-%s/?bb=l_y5osp1gK4un5gnD', strtolower( $loc[ 'city' ] ), strtolower( $loc[ 'state' ] ), $zip );

        $caPath    = $this->ensureCACert();
        $cookieJar = tempnam( sys_get_temp_dir(), 'apt_' );

        for ( $attempt = 1; $attempt <= $maxRetries; $attempt ++ )
        {
            $ch = curl_init();
            curl_setopt_array( $ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_CAINFO         => $caPath,
                CURLOPT_COOKIEJAR      => $cookieJar,
                CURLOPT_COOKIEFILE     => $cookieJar,
                CURLOPT_USERAGENT      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36',
                CURLOPT_HTTPHEADER     => [
                    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                    'Accept-Language: en-US,en;q=0.9',
                    'Accept-Encoding: gzip, deflate, br, zstd',
                    'DNT: 1',
                    'Connection: keep-alive',
                    'Upgrade-Insecure-Requests: 1',
                    'Sec-Fetch-Dest: document',
                    'Sec-Fetch-Mode: navigate',
                    'Sec-Fetch-Site: none',
                ],
                CURLOPT_ENCODING       => '',
            ] );

            $html      = curl_exec( $ch );
            $code      = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            $effective = curl_getinfo( $ch, CURLINFO_EFFECTIVE_URL );
            $err       = curl_error( $ch );
            curl_close( $ch );

            if ( $html !== false && $code === 200 && strpos( $html, 'Rentals' ) !== false )
            {
                break;
            }

            if ( $attempt < $maxRetries )
            {
                sleep( 3 );
                continue;
            }

            throw new RuntimeException( "Failed after $maxRetries attempts: $err (HTTP $code) → $effective" );
        }

        $debugFile = "debug-{$zip}.html";
        file_put_contents( $debugFile, $html );

        // Block check
        if ( stripos( $html, 'You must choose a place to search' ) !== false )
        {
            throw new RuntimeException( "Blocked for ZIP $zip. Try manual search flow." );
        }

        libxml_use_internal_errors( true );
        $doc = new DOMDocument();
        $doc->loadHTML( $html, LIBXML_NOERROR );
        $xpath = new DOMXPath( $doc );

        $result = [
            'zip'              => $zip,
            'city_state'       => implode( '-', [ $loc[ 'city' ], $loc[ 'state' ] ] ),
            'url'              => $effective,
            'http_code'        => $code,
            'total_apartments' => null,
            'active_for_rent'  => null,
            'debug_file'       => $debugFile,
        ];

        // Active: From header like "# Rentals" or "X available rental units"
        $activeNode = $xpath->query( '//h1[contains(text(), "Rentals")] | //*[contains(text(), "available rental units")]' )->item( 0 );
        if ( $activeNode )
        {
            if ( preg_match( '/(\d{1,3}(?:,\d{3})*)/', $activeNode->textContent, $m ) )
            {
                $result[ 'active_for_rent' ] = (int) str_replace( ',', '', $m[ 1 ] );
            }
        }

        // Total: From "over X thousand currently available apartments"
        $totalNode = $xpath->query( '//p[contains(text(), "thousand currently available")]' )->item( 0 );
        if ( $totalNode )
        {
            if ( preg_match( '/over\s+(\d+)\s+thousand/', $totalNode->textContent, $m ) )
            {
                $result[ 'total_apartments' ] = (int) $m[ 1 ] * 1000;
            }
        }

        // Fallback regex for both
        if ( $result[ 'active_for_rent' ] === null )
        {
            if ( preg_match( '/\d{1,3}(?:,\d{3})*\s+Rentals/', $html, $m ) )
            {
                $result[ 'active_for_rent' ] = (int) str_replace( ',', '', $m[ 0 ] );
            }
        }
        if ( $result[ 'total_apartments' ] === null )
        {
            if ( preg_match( '/over\s+(\d+)\s+thousand\s+currently\s+available/', $html, $m ) )
            {
                $result[ 'total_apartments' ] = (int) $m[ 1 ] * 1000;
            }
        }

        @unlink( $cookieJar );

        return $result;
    }
}
