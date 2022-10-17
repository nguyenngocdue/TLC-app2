<?php

namespace App\View\Components\Controls;

use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Translationtime extends Component
{

    private $control;
    private $id;
    private $columnName;
    private $valColumnNames;
    private $timeControls;
    private $tablePath;
    public function __construct($timeControls, $valColumnNames, $columnName, $control, $id, $tablePath)
    {

        $this->control = $control;
        $this->id = $id;
        $this->columnName = $columnName;
        $this->valColumnNames = $valColumnNames;
        $this->timeControls = $timeControls;
        $this->tablePath = $tablePath;
    }

    public function render()
    {

        $selected = $this->id;
        $columnName = $this->columnName;

        // get table's name in database
        $insTable = new $this->tablePath;
        $tableName = $insTable->getTable();
        $currentTable = DB::table($tableName)->where('id', $selected)->first();

        $day = '';
        $valColumnNames = $this->valColumnNames;
        if (in_array($columnName, $valColumnNames) && isset($currentTable->$columnName)) {
            $day = $currentTable->$columnName;
        }

        $control = $this->control;
        $dateTimeInstance = date_create(str_replace('-', '/', $day));

        $dateTime = date_format($dateTimeInstance, "d/m/Y h:i:s");

        $week = $dateTimeInstance->format("W");
        $date = $dateTimeInstance->format("d/m/Y");
        $month = $dateTimeInstance->format("M");
        $time = $dateTimeInstance->format('H:i:s A');
        $monthNumber = date("m", strtotime($month));
        $year =  $dateTimeInstance->format("Y");
        $quater = ceil($monthNumber / 3);

        $timeControls = $this->timeControls;
        $dataTime = [
            $timeControls[0] => $time,
            $timeControls[1] => $date,
            $timeControls[2] => $monthNumber . '-' . $year,
            $timeControls[3] => 'W' . $week . '-' . $year,
            $timeControls[4] => 'Q' . $quater . '-' . $year,
            $timeControls[5] => $year,
            $timeControls[6] => $dateTime,
        ];
        return view('components.controls.translationtime')->with(compact('day', 'dataTime', 'control'));
    }
}
