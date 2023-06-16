<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {

        $collect = User::getCollection()->whereIn('id', [1, 2, 5]);
        dump($collect);

        $u = User::findFromCache(1);
        dump($u);
        $u = User::findFromCache(38);
        dump($u);

        $dataSource = [];

        return view("welcome-fortune", [
            'dataSource' => $dataSource,
        ]);
    }
}
