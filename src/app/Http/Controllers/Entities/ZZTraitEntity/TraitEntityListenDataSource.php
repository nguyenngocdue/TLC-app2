<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\Json\Listeners;
use App\Utils\Support\Json\Props;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait TraitEntityListenDataSource
{
    private $debugListenDataSource = false;

    private function dump2($title, $content)
    {
        if ($this->debugListenDataSource) {
            echo "$title";
            dump($content);
        }
    }

    private function renderListenDataSource()
    {
        $sp = $this->superProps;
        $this->dump2("SuperProps", $sp);
        $toBeLoaded = [];
        $listen_to_fields = [];
        $listen_to_attrs = [];

        $listeners = Listeners::getAllOf($this->type);
        foreach ($listeners as $listener) {
            $listen_to_fields0 = $listener['listen_to_fields'];
            $listen_to_fields0 = $listen_to_fields0 ? explode(",", $listen_to_fields0) : [];
            $listen_to_fields[] = array_map(fn ($i) => $sp['props']["_" . $i]['relationships']['table'], $listen_to_fields0);

            $listen_to_attrs0 = $listener['listen_to_attrs'];
            $listen_to_attrs[] = $listen_to_fields0 ? explode(",", $listen_to_attrs0) : [];
        }

        foreach ($sp['props'] as $prop) {
            $relationships = $prop['relationships'];
            if (isset($relationships['table'])) {
                $table = $relationships['table'];
                $toBeLoaded[] = $table;
            }
        }

        $listen_to_fields = Arr::flatten($listen_to_fields);
        $listen_to_attrs = Arr::flatten($listen_to_attrs);
        $toBeLoaded = [...$toBeLoaded, ...$listen_to_fields];

        $this->dump2('listen_to_fields', $listen_to_fields);
        $this->dump2('listen_to_attrs', $listen_to_attrs);

        $extraColumns = [];
        foreach ($listen_to_fields as $i => $table) {
            $extraColumns[$table][] = $listen_to_attrs[$i];
        }
        $this->dump2("Extra Columns to load: ", $extraColumns);

        $toBeLoaded = array_unique($toBeLoaded);
        $this->dump2("To Be Loaded Tables", $toBeLoaded);


        $matrix = [];
        foreach ($toBeLoaded as $table) {
            // $columns = DBTable::getColumnNames($table);
            $props = Props::getAllOf($table);
            $availableColumnNames = array_values(array_map(fn ($prop) => $prop['column_name'], $props));
            // dump($availableColumnNames);

            $defaultColumns =  ['id', 'name', 'description'];
            if (isset($extraColumns[$table])) $defaultColumns = [...$defaultColumns, ...$extraColumns[$table]];
            // $matrix[$table] = [...$defaultColumns, ...$columns];
            $matrix[$table] = array_intersect($defaultColumns, $availableColumnNames);
            $diff = array_diff($matrix[$table], $defaultColumns);

            if (sizeof($diff) > 0) $this->dump2("Column not found in $table", $diff);
        }
        $this->dump2("Matrix", $matrix);

        $result = [];

        $columnsWithOracy = [];
        foreach ($matrix as $table => $columns) {
            $modelPath = "App\\Models\\" . Str::singular($table);
            $nameless = (new $modelPath)->nameless;
            $columnsWithoutOracy = array_filter($columns, fn ($column) => !str_contains($column, "()"));
            $columnsWithOracy[$table] = array_values(array_filter($columns, fn ($column) => str_contains($column, "()")));
            $rows = DB::table($table)->select($columnsWithoutOracy);
            if (!$nameless) $rows = $rows->orderBy('name');
            $objectRows = $rows->get()->toArray();
            // $result[$table] = $objectRows;
            $result[$table] = array_map(fn ($o) => (array)$o, $objectRows);
        }

        $this->dump2("columnsWithOracy", $columnsWithOracy);
        foreach ($columnsWithOracy as $table => $listOfFn) {
            $modelPath = "App\\Models\\" . Str::singular($table);
            if (sizeof($listOfFn) > 0) {
                foreach ($listOfFn as $fn) {
                    $fn = substr($fn, 0, strlen($fn) - 2); //Remove ()
                    foreach ($result[$table] as &$row) {
                        $model = $modelPath::find($row['id']);
                        $row[$fn] = $model->getCheckedByField($fn)->pluck('id')->toArray();
                    }
                }
            }
        }

        $this->dump2("Result", $result);
        return $result;
    }

    private function getListeners()
    {
        $sp = $this->superProps;
        $result = array_values(Listeners::getAllOf($this->type));
        foreach ($result as &$line) {
            unset($line['name']);
            if (!isset($sp['props']["_" . $line['column_name']]['relationships']['table'])) {
                $line['table_name'] = $line['column_name'] . " is not a HasDataSource control";
            } else {
                $line['table_name'] = $sp['props']["_" . $line['column_name']]['relationships']['table'];
            }
            $line['listen_to_attrs'] = $line['listen_to_attrs'] ? explode(",", $line['listen_to_attrs']) : [];
            $line['listen_to_fields'] = $line['listen_to_fields'] ? explode(",", $line['listen_to_fields']) : [];
            $line['triggers'] = explode(",", $line['triggers']);

            $line['listen_to_tables'] = array_map(fn ($control) => $sp['props']["_" . $control]['relationships']['table'], $line['listen_to_fields']);
        }

        return $result;
    }
}
