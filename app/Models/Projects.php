<?php

namespace App\Models;

use Illuminate\Support\Str;

class Projects
{
    private static ?self $instance = null;

    private array $projects;

    private function __construct()
    {
        $projects = [];

        $project                    = new \stdClass();
        $project->name              = 'Backus Property';
        $project->slug              = Str::slug( $project->name );
        $projects[ $project->slug ] = $project;

        ksort( $projects );

        $this->projects = $projects;
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
        return $this->projects;
    }

    public function project( $slug ): ?\stdClass
    {
        return $this->projects[ $slug ] ?? null;
    }
}
