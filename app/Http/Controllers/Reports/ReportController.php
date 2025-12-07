<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index( Request $request ): View
    {
        $reports = reports()->all();

        return view( 'reports.index', [ 'reports' => $reports ] );
    }

    public function analysis( Request $request, string $slug ): Factory|\Illuminate\Contracts\View\View|RedirectResponse
    {
        $report    = reports()->report( $slug );
        $view_file = resource_path( 'views/reports/' . $slug . '.blade.php' );
        $exists    = $report && file_exists( $view_file );
        if ( ! $exists )
        {
            alert()->error( 'Analysis for this report has not completed yet.', 'Coming Soon!' )->persist();

            return redirect()->route( 'reports.index' );
        }

        return view( 'reports.' . $slug, [ 'report' => $report ] );
    }
}
