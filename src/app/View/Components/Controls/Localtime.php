<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Localtime extends Component
{


    public function __construct(private $timeControls, private $valColumnNames, private $control, private $id, private $tablePath, private $colName, private $labelName)
    {
    }

    public function render()
    {
        $selected = $this->id;
        $colName = $this->colName;
        $control = $this->control;
        $timeControls = $this->timeControls;
        $labelName = $this->labelName;


        // get database table name
        $insTable = new $this->tablePath;
        $tableName = $insTable->getTable();
        $currentTable = DB::table($tableName)->where('id', $selected)->first();

        $day = '';
        $valColumnNames = $this->valColumnNames;

        if (in_array($control, $timeControls) && isset($currentTable->$colName)) $day = $currentTable->$colName;

        $dateTimeInstance = date_create(str_replace('-', '/', $day));
        $dateTime = date_format($dateTimeInstance, "d/m/Y H:i:s");

        $week = $dateTimeInstance->format("W");
        $date = $dateTimeInstance->format("d/m/Y");
        $month = $dateTimeInstance->format("M");
        $time = $dateTimeInstance->format('H:i:s');
        $monthNumber = date("m", strtotime($month));
        $year =  $dateTimeInstance->format("Y");
        $quater = ceil($monthNumber / 3);

        $dataTime = [
            $timeControls[0] => $time,
            $timeControls[1] => $date,
            $timeControls[2] => $monthNumber . '-' . $year,
            $timeControls[3] => 'W' . $week . '-' . $year,
            $timeControls[4] => 'Q' . $quater . '-' . $year,
            $timeControls[5] => $year,
            $timeControls[6] => $dateTime,
        ];

        return view('components.controls.localtime')->with(compact('day', 'dataTime', 'control', 'colName', 'labelName'));
    }
}
