<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {

        $flatTree = [
            [
                'column_name' => 'id',
                'column_type' => 'int',
            ],
            [
                'column_name' => 'h1',
                'column_type' => 'static',
            ],
            [
                'column_name' => 'id1',
                'column_type' => 'int',
            ],
            [
                'column_name' => 'h2a',
                'column_type' => 'static',
            ],
            [
                'column_name' => 'h2b',
                'column_type' => 'static',
            ],
            [
                'column_name' => 'id2',
                'column_type' => 'int',
            ],
            [
                'column_name' => 'id',
                'column_type' => 'static',
                'control' => 'z_divider',
            ],
        ];

        // $root = [];
        // $node = [];
        // $fakeNodeCount =0;
        // foreach($flatTree as $prop){
        //     if($prop['column_type'] !== 'static'){
        //         if(sizeof($node) === 0){
        //             $node['fakeNode'.$fakeNodeCount]['title'] = "FakeNodeTitle";
        //             $node['fakeNode'.$fakeNodeCount]['children'][] = $prop;
        //             $fakeNodeCount++;
        //         } else {
        //             $node['fakeNode'.$fakeNodeCount]['children'][] = $prop;
        //         }
        //     }
        //     else {
        //         if()
        //     }
        // }

        return view("welcome-fortune", [
            // ''
        ]);
    }
}
