<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Support\Str;

class Report extends AbstractBaseModel
{
    protected $table = 'reports';

    public static function booted(): void
    {
        static::creating( function ( Report $report ) {
            if ( is_null( $report->slug ) )
            {
                $report->make_slug();
            }
        } );
    }

    public function view(): ?string
    {
        $view_file = resource_path( 'views/reports/' . $this->slug . '.blade.php' );
        $exists    = file_exists( $view_file );

        return $exists ? $view_file : null;
    }

    public function make_slug( string $slug = null ): void
    {
        if ( is_null( $slug ) )
        {
            $slug = Str::slug( $this->title, '-' );
        }

        $query = Report::where( 'slug', '=', $slug );
        if ( ! is_null( $this->id ) )
        {
            $query = $query->where( 'id', '!=', $this->id );
        }
        if ( $query->exists() )
        {
            $original_slug = $slug;
            $index         = 1;
            while( $query->exists() )
            {
                $slug = $original_slug . '-' . $index;
            }
        }

        $this->slug = $slug;
    }

    protected function casts(): array
    {
        return [
            'categories' => 'array',
        ];
    }
}
