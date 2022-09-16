<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Translationtime extends Component
{

    private $control;
    private $id;
    private $columnName;
    public function __construct($columnName, $control, $id)
    {

        $this->control = $control;
        $this->id = $id;
        $this->columnName = $columnName;
    }

    public function render()
    {

        $selected = $this->id;
        $columnName = $this->columnName;
        $currentUser = DB::table('users')->where('id', $selected)->first();
        $day = $columnName === "created_at" ? $currentUser->created_at : $currentUser->updated_at;

        $control = $this->control;
        $dateTimeInstance = date_create(str_replace('-', '/', $day));

        $dateTime = date_format($dateTimeInstance, "d/m/Y h:i:s");
        // $exploreDateTime = explode(" ", $dateTime);
        // dd($exploreDateTime);

        $week = $dateTimeInstance->format("W");
        $date = $dateTimeInstance->format("d/m/Y");
        $month = $dateTimeInstance->format("M");
        $time = $dateTimeInstance->format('H:i:s A');
        $monthNumber = date("m", strtotime($month));
        $year =  $dateTimeInstance->format("Y");
        $quater = ceil($monthNumber / 3);

        $dataTime = [
            'datetime' => $dateTime,
            'picker_date' => $date,
            'picker_time' => $time,
            'picker_year' => $year,
            'picker_week' => 'W' . $week . '-' . $year,
            'picker_month' => $monthNumber . '-' . $year,
            'picker_quater' => 'Q' . $quater . '-' . $year,
        ];





        return view('components.controls.translationtime')->with(compact('dataTime', 'control'));
    }
}
