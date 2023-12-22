<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use Illuminate\Http\Request;

class PublicHolidaysController extends Controller
{
    use TraitViewAllFunctions;
    public function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {
        return view(
            'utils.public-holidays',
            [
                'topTitle' => 'Public Holidays'
            ]
        );
    }
}
