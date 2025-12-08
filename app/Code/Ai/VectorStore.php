<?php

namespace App\Code\Ai;


use OpenAI;
use OpenAI\Client;

class VectorStore
{
    public static string $embedding_model     = 'text-embedding-3-small';
    public static int    $expected_dimensions = 1536;

    private string $store;
    private string $vector_dir;

    public function __construct( string $store )
    {
        $this->store      = $store;
        $this->vector_dir = storage_path( 'vectors' );

        if ( ! is_dir( $this->vector_dir ) )
        {
            mkdir( $this->vector_dir, 0755, true );
        }
    }

    private static function fix_encoding( string $text ): string
    {
        // Fix common encoding issues
        $clean = mb_convert_encoding( $text, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' );

        // Remove any remaining malformed sequences
        return iconv( 'UTF-8', 'UTF-8//IGNORE', $clean ) ?: '';
    }

    public function store(): string
    {
        return $this->store;
    }

    public function vector_dir(): string
    {
        return $this->vector_dir;
    }

    public function store_file(): string
    {
        return $this->vector_dir . '/' . $this->store . '.store';
    }

    public function meta_file(): string
    {
        return $this->vector_dir . '/' . $this->store . '.meta.json';
    }

    public function populate(): void
    {
        file_put_contents( $this->store_file(), '' );

        $api_key = config( 'openai.api_key' );

        $client = ( new OpenAI\Factory() )->withApiKey( $api_key )->make();

        ini_set( 'memory_limit', '512M' );
        set_time_limit( 600 );

        $written = 0;
        foreach ( glob( $this->vector_dir . '/docs/*' ) as $file )
        {
            $document             = new \stdClass();
            $document->file       = $file;
            $document->name       = basename( $file );
            $document->type       = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
            $document->content    = $this->get_file_contents( $document );
            $document->embeddings = $this->get_embeddings( $client, $document );
            if ( ! $document->embeddings )
            {
                echo "❌ Embeddings not created for " . $document->name . "\n<br>";
            }

            if ( $document->embeddings && $this->process_embedding( $document ) )
            {
                $written ++;
                echo "✅ Added embedding ($written) for: " . $document->name . " | " . str_replace( "\n", ' ', substr( trim( $document->content ), 0, 70 ) ) . "...\n<br>";
            }
        }
        $meta = [
            'model'       => self::$embedding_model,
            'dimension'   => self::$expected_dimensions,
            'generatedAt' => date( DATE_ATOM ),
            'count'       => $written,
        ];
        file_put_contents( $this->meta_file(), json_encode( $meta, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );

        echo "\n<br>✓ Vector store populated with $written documents (dimension: " . self::$expected_dimensions . ")\n<br>";


        dd( 'here' );

    }

    private function get_file_contents( $document ): string
    {
        if ( $document->type === 'pdf' )
        {
            return $this->extract_pdf_with_poppler( $document->file );
        }

        $content = file_get_contents( $document->file );

        return self::fix_encoding( $content );
    }

    private function extract_pdf_with_poppler( string $path ): string
    {
        $tmp = tempnam( sys_get_temp_dir(), 'pdf' );
        $cmd = "pdftotext -enc UTF-8 " . escapeshellarg( $path ) . " " . escapeshellarg( $tmp );
        shell_exec( $cmd );

        return file_get_contents( $tmp ) ?: '';
    }

    private function get_embeddings( Client $client, \stdClass $document ): ?array
    {
        $chunks = $this->chunk_text( $document->content );

        $all_embeddings = [];
        foreach ( $chunks as $index => $chunk )
        {
            try
            {
                $response = $client->embeddings()->create( [
                                                               'model'      => self::$embedding_model,
                                                               'input'      => $chunk,
                                                               'dimensions' => self::$expected_dimensions,
                                                           ] );

                $embeddings = $response->embeddings[ 0 ]->embedding ?? null;
                if ( ! $embeddings || ! is_array( $embeddings ) )
                {
                    throw new \RuntimeException( "Invalid embedding returned" );
                }
                $all_embeddings[] = [
                    'chunk'      => $chunk,
                    'embeddings' => $embeddings,
                    'index'      => $index
                ];
            }
            catch( \Throwable $e )
            {
                echo "❌ Error embedding chunk $index of {$document->name}: {$e->getMessage()}<br>";
            }
        }

        if ( ! is_array( $all_embeddings ) )
        {
            return null;
        }

        return $all_embeddings;
    }

    private function chunk_text( string $text, int $max_tokens = 2000 ): array
    {
        // Simple token approximation (1 token ≈ 4 chars in English)
        $approx_token_size = 4;
        $max_chunk_length  = $max_tokens * $approx_token_size;

        $chunks = [];
        $length = strlen( $text );

        for ( $i = 0; $i < $length; $i += $max_chunk_length )
        {
            $chunks[] = substr( $text, $i, $max_chunk_length );
        }

        return $chunks;
    }

    private function process_embedding( \stdClass $document ): bool
    {
        foreach ( $document->embeddings as $chunk )
        {
            $embeddings = array_map( static function ( $v ) {
                return is_numeric( $v ) ? (float) $v : 0.0;
            }, $chunk[ 'embeddings' ] );

            if ( count( $embeddings ) !== self::$expected_dimensions )
            {
                echo "! Skipped document due to dimension mismatch (got " . count( $document->embeddings ) . ", expected " . self::$expected_dimensions . ").\n<br>";

                continue;
            }

            $json_line = json_encode( [
                                          'embedding' => $embeddings,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        'content' => $chunk[ 'chunk' ],
                                                                                                                                                                                                                                                                                                                                                                                                                                                                        'sourceType' => $document->type,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          'sourceName' => $document->name,
                                          'chunk'     => $chunk[ 'index' ],
                                          'id'        => md5( $chunk[ 'chunk' ] ),
                                          'metadata'  => [],
                                      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

            file_put_contents( $this->store_file(), $json_line . "\n", FILE_APPEND );
        }

        return true;
    }
}
