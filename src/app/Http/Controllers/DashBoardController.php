<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $sideBar = json_decode(file_get_contents(storage_path() . "/json/view/dashboard/sidebarProps.json"), true);
        // foreach($sideBar as $item){
        //     dd($item);
        //     foreach($item as $a){
        //         dd($a);
        //     }
        // }
        return view('dashboards.dashboard');
    }
}
