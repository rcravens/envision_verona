<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index( Request $request ): View
    {
        $reports = Report::orderBy( 'slug' )->get();

        return view( 'reports.index', [ 'reports' => $reports ] );
    }

    public function analysis( Request $request, Report $report ): Factory|\Illuminate\Contracts\View\View|RedirectResponse
    {
        $view_file = $report->view();
        if ( ! $view_file )
        {
            alert()->error( 'Analysis for this report has not completed yet.', 'Coming Soon!' )->persist();

            return redirect()->route( 'reports.index' );
        }

        return view( 'reports.' . $report->slug, [ 'report' => $report ] );
    }
}
