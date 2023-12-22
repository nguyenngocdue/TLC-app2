<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Controller;
use App\Http\Resources\HolidayCollection;
use App\Models\Public_holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicHolidaysControllerApi extends Controller
{
    public function index(Request $request)
    {
        $currentYear = $request->year;
        $publicHoliday = Public_holiday::where('year',$currentYear)->get()->toArray();
        $results = [];
        foreach($publicHoliday as $item){
            $key = $item['ph_date'] . "-". $item['name'];
            if(!isset($results[$key])) {
                $item['workplace_id'] = [$item['workplace_id']];
                $results[$key] = $item;
            }
            else $results[$key]['workplace_id'][] = $item['workplace_id'];
        }
        $results = array_values($results);
        return ['hits' => new HolidayCollection(collect($results)), 'meta' => $this->checkYearCurrent($currentYear)];
    }
    private function checkYearCurrent($year){
        $currentYear = Carbon::now()->year;
        if($currentYear == $year) return null;
        else if ($currentYear > $year) return $year."-12-30";
        else return $year."-01-01";
    }
}
