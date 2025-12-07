<?php

namespace App\Utils;

class AlertHelper
{
    private $id = null;

    private array $config = [
        'timer' => 3000
    ];

    public function __construct()
    {
        $this->id = uniqid();
    }

    public function info( string $message, string $title = 'Info' ): static
    {
        $this->create( 'info', $message, $title );

        return $this;
    }

    public function warn( string $message, string $title = 'Warning' ): static
    {
        $this->create( 'warning', $message, $title );

        return $this;
    }

    public function error( string $message, string $title = 'Error' ): static
    {
        $this->create( 'error', $message, $title );

        return $this;
    }

    public function success( string $message, string $title = 'Success' ): static
    {
        $this->create( 'success', $message, $title );

        return $this;
    }

    public function timer( int $timer ): static
    {
        $this->config[ 'timer' ] = $timer;

        $this->flash_alert();

        return $this;
    }

    public function persist(): static
    {
        $this->config[ 'timer' ] = 0;

        $this->flash_alert();

        return $this;
    }

    private function create( string $type, string $message, string $title ): void
    {
        $this->config[ 'title' ]   = $title;
        $this->config[ 'message' ] = $message;
        $this->config[ 'type' ]    = $type;

        $this->flash_alert();
    }

    private function flash_alert(): void
    {
        if ( ! session()->has( 'custom_alerts' ) )
        {
            session()->flash( 'custom_alerts', [] );
        }

        $existing_alerts              = session( 'custom_alerts', [] );
        $existing_alerts[ $this->id ] = $this->config;

        session()->put( 'custom_alerts', $existing_alerts );
    }
}
