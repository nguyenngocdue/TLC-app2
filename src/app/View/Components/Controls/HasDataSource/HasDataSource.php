<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasDataSource
{
    private $loadFilterColumnByPHP = true;

    function getRelatedModelEOO($eloquentOrOracy)
    {
        $sp = SuperProps::getFor($this->type);
        $name = "_" . $this->name;
        $params = $sp['props'][$name]['relationships'][$eloquentOrOracy];
        $relatedModel = $params[1];
        return $relatedModel;
    }

    function getTableEOO($eloquentOrOracy)
    {
        $relatedModel = $this->getRelatedModelEOO($eloquentOrOracy);
        $table = (new $relatedModel)->getTable();
        return $table;
    }

    function getDataSourceEOO($eloquentOrOracy)
    {
        $relatedModel = $this->getRelatedModelEOO($eloquentOrOracy);
        if ($this->loadFilterColumnByPHP) {
            $sp = SuperProps::getFor($this->type);
            $name = "_" . $this->name;
            $table = (new $relatedModel)->getTable();
            $dataSource = DB::table($table);

            $filter_columns = $sp['props'][$name]['relationships']['filter_columns'];
            $filter_values = $sp['props'][$name]['relationships']['filter_values'];

            // dump($sp['props'][$name]['relationships']['filter_columns']);
            // dump(($filter_columns));
            // dump(($filter_values));
            for ($i = 0; $i < sizeof($filter_columns); $i++) {
                // dump($filter_columns[$i] . " - " . $filter_values[$i]);
                $dataSource = $dataSource->where($filter_columns[$i], $filter_values[$i]);
            }
            $dataSource = $dataSource->get();
            // $dataSource = DB::table($table)->get();
        } else {
            $dataSource = $relatedModel::all();
        }

        $result = [];
        foreach ($dataSource as $row) {
            $idStr = Str::makeId($row->id);
            $result[] = [
                "value" => $row->id,
                "label" => $row->name,
                "description" => isset($row->description) ? ($row->description . " " . $idStr) : $idStr,
            ];
        }
        return $result;
    }

    function warningIfDataSourceIsEmpty($dataSource)
    {
        $class = "bg-white border border-gray-300  text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500";
        if (is_null($dataSource)) return "<p class='$class text-red-400'>DataSource is NULL.</p>";
        if (sizeof($dataSource) === 0) return "<p class='$class text-orange-400'>DataSource is EMPTY.</p>";
    }

    function getSpan()
    {
        $sp = SuperProps::getFor($this->type);
        $name = "_" . $this->name;
        return $sp['props'][$name]['relationships']['radio_checkbox_colspan'];
    }
}
