<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\Public_holiday;
use App\Models\User_time_keep_type;
use App\Models\Workplace;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class PublicHolidayController extends Controller
{
    use TraitViewAllFunctions;
    public function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {
        return view(
            'utils.public-holiday',
            [
                'topTitle' => 'Public Holiday Company'
            ]
        );
    }
}
