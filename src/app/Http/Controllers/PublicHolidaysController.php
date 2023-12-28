<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\Workplace;
use App\Utils\GridCss;
use App\Utils\Support\Calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

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
                'topTitle' => 'Public Holidays',
            ]
        );
    }
    
}
