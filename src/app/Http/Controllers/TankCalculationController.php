<?php

namespace App\Http\Controllers;

class TankCalculationController
{
    public function getType()
    {
        return "dashboard";
    }

    public function index()
    {

        return view('tank-calculation');
    }
}
