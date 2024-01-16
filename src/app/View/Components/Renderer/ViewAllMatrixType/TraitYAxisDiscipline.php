<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Prod_discipline;
use Illuminate\Support\Facades\Log;

trait TraitYAxisDiscipline
{
    private function getDisciplines()
    {
        $result = [];
        $disciplines = Prod_discipline::all();
        foreach ($disciplines as $discipline) {
            $result[$discipline->id] = $discipline;
        }
        return $result;
    }

    protected function getXAxis2ndHeader($xAxis)
    {
        $result = [];
        $disciplines = $this->getDisciplines();

        foreach ($xAxis as $line) {
            if (isset($line['prod_discipline_id'])) {
                $discipline = $disciplines[$line['prod_discipline_id']];
                $value = $discipline->description;
                $cell_title = "";
                $cell_class = $discipline->css_class . " align-top";

                // if (isset($line['default_monitors'])) {
                //     $count = $line['default_monitors']->count();
                //     if ($count) {
                //         $value .= "<br/>(" . $count . '<i class="fa-duotone fa-user"></i>' . ")";
                //     }
                //     $cell_title = "Default 3rd Party to Sign Off:\n" . $line['default_monitors']->join("\n");
                // }
                $result[$line['dataIndex']] = (object)[
                    'value' => $value,
                    'cell_title' => $cell_title,
                    'cell_class' => $cell_class,
                ];
            }
        }
        return $result;
    }
}
