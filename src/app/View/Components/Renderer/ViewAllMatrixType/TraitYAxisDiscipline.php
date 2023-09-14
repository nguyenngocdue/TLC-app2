<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Prod_discipline;

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
        $icons = [
            1 => "<span class=''>PPR</span>",
            2 => "<span class=''>STR</span>",
            3 => "<span class=''>FIT</span>",
            4 => "<span class=''>MEPF</span>",
            5 => "<span class=''>WH</span>",
            6 => "<span class=''>PAS</span>",
            7 => "<span class=''>QAQC</span>",
        ];
        $bg = [
            1 => "bg-stone-400 text-stone-600",
            2 => "bg-slate-400 text-slate-600",
            3 => "bg-emerald-700 text-emerald-300",
            4 => "bg-indigo-300 text-indigo-700",
            5 => "bg-lime-300 text-lime-700",
            6 => "bg-orange-300 text-orange-700",
            7 => "bg-blue-400 text-blue-600",
        ];
        foreach ($xAxis as $line) {
            if (isset($line['prod_discipline_id'])) {
                $discipline = $disciplines[$line['prod_discipline_id']];
                $result[$line['dataIndex']] = (object)[
                    'value' => $icons[$discipline->id] ?? "",
                    'cell_title' => $discipline->name,
                    'cell_class' => $bg[$discipline->id] ?? "",
                ];
            }
        }
        return $result;
    }
}
