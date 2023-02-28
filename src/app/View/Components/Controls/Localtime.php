<?php

namespace App\View\Components\Controls;

use App\Utils\Support\JsonControls;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Localtime extends Component
{
    public function __construct(
        private $control,
        private $id,
        private $modelPath,
        private $colName,
        private $label,
    ) {
    }

    public function render()
    {
        $selected = $this->id;
        $colName = $this->colName;
        $control = $this->control;
        $label = $this->label;
        $timeControls = JsonControls::getDateTimeControls();

        // get database table name
        $insTable = new $this->modelPath;
        $tableName = $insTable->getTable();
        $currentTable = DB::table($tableName)->where('id', $selected)->first();

        $day = '';


        if (in_array($control, $timeControls) && isset($currentTable->$colName)) $day = $currentTable->$colName;

        $dateTimeInstance = date_create(str_replace('-', '/', $day));
        $dateTime = date_format($dateTimeInstance, "d/m/Y H:i:s");

        $week = $dateTimeInstance->format("W");
        $date = $dateTimeInstance->format("d/m/Y");
        $month = $dateTimeInstance->format("M");
        $time = $dateTimeInstance->format('H:i:s');
        $monthNumber = date("m", strtotime($month));
        $year =  $dateTimeInstance->format("Y");
        $quarter = ceil($monthNumber / 3);

        $dataTime = [
            'picker_datetime' => $dateTime,
            'picker_time' => $time,
            'picker_date' => $date,
            'picker_month' => $monthNumber . '-' . $year,
            'picker_week' => 'W' . $week . '-' . $year,
            'picker_quarter' => 'Q' . $quarter . '-' . $year,
            'picker_year' => $year,
        ];

        return view('components.controls.localtime')->with(compact('day', 'dataTime', 'control', 'colName', 'label'));
    }
}
