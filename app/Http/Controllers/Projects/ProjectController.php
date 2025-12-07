<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index( Request $request ): View
    {
        $projects = projects()->all();

        return view( 'projects.index', [ 'projects' => $projects ] );
    }

    public function analysis( Request $request, string $slug ): Factory|\Illuminate\Contracts\View\View|RedirectResponse
    {
        $project   = projects()->project( $slug );
        $view_file = resource_path( 'views/projects/' . $slug . '.blade.php' );
        $exists    = $project && file_exists( $view_file );
        if ( ! $exists )
        {
            alert()->error( 'Analysis for this project has not completed yet.', 'Coming Soon!' )->persist();

            return redirect()->route( 'projects.index' );
        }

        return view( 'projects.' . $slug, [ 'project' => $project ] );
    }
}
