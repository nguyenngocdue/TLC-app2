<?php

namespace App\Http\Controllers;

use App\Utils\Support\Json\SuperProps;

class WelcomeController extends Controller
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
        // dump("SUPER PROPS");
        // dump(SuperProps::getFor('department'));
        // dump(SuperProps::getFor('hse_incident_report'));
        // dump(SuperProps::getFor('attachment'));
        // dump(SuperProps::getFor('user'));
        dump(SuperProps::getFor('prod_order'));
        // dump(SuperProps::getFor('prod_routing'));
        // dump(SuperProps::getFor('zunit_test_1'));
        // dump(SuperProps::getFor('zunit_test_2'));
        // dump(SuperProps::getFor('zunit_test_9'));
        return view(
            'welcome',
            []
        );
    }
}
