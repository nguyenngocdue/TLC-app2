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
                $result[$line['dataIndex']] = (object)[
                    'value' => $discipline->description,
                    'cell_title' => $discipline->name,
                    'cell_class' => $discipline->css_class,
                ];
            }
        }
        return $result;
    }
}
