<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasDataSource
{
    private $loadFilterColumnByPHP = true;

    function getName()
    {
        $name = $this->name;
        if (str_starts_with($this->name, $this->table01Name)) {
            $name = substr($this->name, strlen($this->table01Name) + 1); // table01[hse_incident_report_id][0] => hse_incident_report_id][0]
            $name = substr($name, 0, strpos($name, "]"));
        }
        // dump($this->table01Name, $this->name, $name);
        $name = "_" . $name;
        return $name;
    }

    function getType()
    {
        // dump($this->type);
        $type = $this->type;
        return $type;
    }

    function getRelatedModelEOO($eloquentOrOracy)
    {
        $sp = SuperProps::getFor($this->getType());
        $name = $this->getName();
        if (isset($sp['props'][$name]['relationships'][$eloquentOrOracy])) {
            $params = $sp['props'][$name]['relationships'][$eloquentOrOracy];
            $relatedModel = $params[1];
            return $relatedModel;
        } else {
            dump("$name $eloquentOrOracy is missing ");
            dd();
        }
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
            $sp = SuperProps::getFor($this->getType());
            $name = $this->getName();
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
        $sp = SuperProps::getFor($this->getType());
        $name = $this->getName();
        return $sp['props'][$name]['relationships']['radio_checkbox_colspan'];
    }
}
