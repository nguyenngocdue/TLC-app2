<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Str;

trait HasDataSource
{
    function getDataSource($eloquentOrOracy)
    {
        $sp = SuperProps::getFor($this->type);
        $name = "_" . $this->name;
        $params = $sp['props'][$name]['relationships'][$eloquentOrOracy];
        // dump($params);
        $relatedModel = $params[1];
        $dataSource = $relatedModel::all();
        // dump($dataSource);

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
