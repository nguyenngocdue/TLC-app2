<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\Statuses;
use App\Models\Qaqc_insp_chklst;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_chklst_run;
use App\Models\Qaqc_insp_sheet;
use App\Models\Qaqc_insp_tmpl;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\System\GetSetCookie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $user = User::where('email', 'admin')->first();
        // GetSetCookie::setCookieForever('time_zone', $user->time_zone);
        // $token = $user->createToken('tlc_token')->plainTextToken;
        $array = array(
            'abc' => [
                1, 2, 3
            ],
            'friends' => [
                'Chris',
                'Jim',
                'Lynn',
                'Jeff',
                'Joanna',
            ],
            'websites' => [
                'Search' => 'Google',
                'Social' => 'Facebook',
                'New' => 'NY Times'
            ],


        );
        // function quickSort($array)
        // {
        //     $length = count($array);

        //     // Return if the array is empty or has only one element
        //     if ($length <= 1) {
        //         return $array;
        //     }

        //     // Select the pivot element
        //     $pivot = $array[0];

        //     // Initialize the left and right arrays
        //     $left = $right = array();

        //     // Partition the array into left and right subarrays
        //     for ($i = 1; $i < $length; $i++) {
        //         if ($array[$i] < $pivot) {
        //             $left[] = $array[$i];
        //         } else {
        //             $right[] = $array[$i];
        //         }
        //     }

        //     // Recursively sort the left and right subarrays
        //     $left = quickSort($left);
        //     $right = quickSort($right);

        //     // Concatenate the left array, the pivot element, and the right array
        //     return array_merge($left, array($pivot), $right);
        // }

        // // Example usage
        // $array = array(3, 4, 2, 1, 6, 5);
        // $sortedArray = quickSort($array);
        // dd($sortedArray);
        $arrayIdFail = [2, 6];
        $arrayIdOnHold = [4, 8];
        $model = Qaqc_insp_chklst_run::find(41);
        $qaqcInspChklstLines = $model->getLines;
        dd($model->getLines);
        $arrayControlValueId = [];
        foreach ($qaqcInspChklstLines as $qaqcInspChklstLine) {
            array_push($arrayControlValueId, $qaqcInspChklstLine['qaqc_insp_control_value_id']);
        }
        dd($arrayControlValueId);
        if (Arr::containsEach($arrayIdFail, $arrayControlValueId)) {
            $status = 'fail';
        } else if (!Arr::containsEach($arrayIdFail, $arrayControlValueId) && Arr::containsEach($arrayIdOnHold, $arrayControlValueId)) {
            $status = 'on_hold';
        } else {
            $status = 'new';
        }
        dd($status);
        // dump(array_merge($array['websites'], $array['friends']));
        // dump(User::where('email', 'admin')->first());
        // dump(CurrentUser::getRoles());
        // dd(CurrentUser::getRoles(User::where('email', 'admin')->first()));
        // return response()->json([
        //     'user' => $user,
        // ]);
    }
}
