<?php

use App\Models\Projects;
use App\Models\Reports;
use App\Utils\AlertHelper;

if ( ! function_exists( 'alert' ) )
{
    function alert( $message = null, $title = null ): AlertHelper
    {
        $alert = new AlertHelper();

        if ( func_num_args() == 0 )
        {
            return $alert;
        }

        return $alert->info( $message, $title );
    }
}

if ( ! function_exists( 'reports' ) )
{
    function reports(): Reports
    {
        return Reports::instance();
    }
}

if ( ! function_exists( 'projects' ) )
{
    function projects(): Projects
    {
        return Projects::instance();
    }
}
