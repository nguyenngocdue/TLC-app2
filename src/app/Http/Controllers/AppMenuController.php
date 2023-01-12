<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibApps;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AppMenuController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $allApps = LibApps::getAll();
        $allApps = array_map(fn ($item) => array_merge($item, ['href' => route(Str::plural($item['name']) . ".index")]), $allApps);
        $allApps = Arr::groupByToChildren($allApps, 'package', 'sub_package');
        // dump($allApps);
        return view(
            'app-menu',
            [
                'allApps' => $allApps
            ]
        );
    }
}
