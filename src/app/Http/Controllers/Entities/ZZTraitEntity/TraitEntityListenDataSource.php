<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\Json\Listeners;
use App\Utils\Support\Json\Props;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait TraitEntityListenDataSource
{
    private $debugListenDataSource = false;

    private function dump2($title, $content, $line = '')
    {
        if ($this->debugListenDataSource) {
            echo "$title line $line";
            dump($content);
        }
    }

    private function refineListenToFieldAndAttr($type)
    {
        $sp = SuperProps::getFor($type);
        $this->dump2("SuperProps", $sp, __LINE__);

        $listen_to_attrs = [];
        $listen_to_tables = [];
        $toBeLoaded = [];

        $listeners = Listeners::getAllOf($type);
        foreach ($listeners as $listener) {
            $listen_to_fields0 = $listener['listen_to_fields'];
            $listen_to_tables[] = array_map(fn ($i) => $sp['props']["_" . $i]['relationships']['table'], $listen_to_fields0);
            $listen_to_attrs[] = $listener['listen_to_attrs'];
        }

        foreach ($sp['props'] as $prop) {
            if ($prop['hidden_edit'] === 'true') continue; // Avoid crash when loading role_set in User edit screen
            $relationships = $prop['relationships'];
            if (isset($relationships['table'])) {
                $table = $relationships['table'];
                $toBeLoaded[] = $table;
            }
        }

        $listen_to_tables = Arr::flatten($listen_to_tables);
        $listen_to_attrs = Arr::flatten($listen_to_attrs);
        $this->dump2('listen_to_tables', $listen_to_tables, __LINE__);
        $this->dump2('listen_to_attrs', $listen_to_attrs, __LINE__);

        $toBeLoaded = array_unique([...$toBeLoaded, ...$listen_to_tables]);
        $this->dump2("To Be Loaded Tables", $toBeLoaded, __LINE__);

        $extraColumns = [];
        //Scan and flag all extra columns in Listeners screen
        foreach ($listen_to_tables as $i => $table) $extraColumns[$table][] = $listen_to_attrs[$i];
        $this->dump2("Extra Columns to load: ", $extraColumns, __LINE__);

        foreach ($sp['props'] as $prop) {
            $relationships = $prop['relationships'];
            $filter_columns = $relationships['filter_columns'] ?? [];

            if (sizeof($filter_columns) > 0) {
                // dump($filter_columns);
                $table = $relationships['table'];
                foreach ($filter_columns as $filter_column) {
                    $extraColumns[$table][] = $filter_column;
                }
                $extraColumns[$table] = array_unique($extraColumns[$table]);
            }
        }

        return [$extraColumns, $toBeLoaded];
    }


    private function deepMerge(array $a1, array $a2)
    {
        $allKeys = [...array_keys($a1), ...array_keys($a2)];
        $result = [];
        foreach ($allKeys as $key) {
            $values1 = $a1[$key] ?? [];
            $values2 = $a2[$key] ?? [];
            $values = array_unique([...$values1, ...$values2]);
            $result[$key] = array_values($values);
        }
        return $result;
    }

    private function getMatrix($types)
    {
        // dump($this->deepMerge([], []));
        // dump($this->deepMerge(["a" => [1, 2, 3]], ["b" => [4, 5, 6]]));
        // dump($this->deepMerge(["a" => [1, 2, 3], "b" => [4, 5, 6, 10]], ["a" => [1, 2, 7, 8, 9], "b" => [10, 11, 12]]));
        // dump($this->type);

        $extraColumns = [];
        $toBeLoaded = [];
        foreach ($types as $type) {
            [$extraColumns0, $toBeLoaded0] = $this->refineListenToFieldAndAttr($type);
            // dump($extraColumns0);
            $extraColumns = $this->deepMerge($extraColumns, $extraColumns0);
            $toBeLoaded = array_unique([...$toBeLoaded, ...$toBeLoaded0]);
        }
        // dump($extraColumns, $toBeLoaded);

        $matrix = [];
        $notFoundInProps = [];
        foreach ($toBeLoaded as $table) {
            $props = Props::getAllOf($table);
            $defaultColumns =  ['id', 'name', 'description'];
            if (isset($extraColumns[$table])) $defaultColumns = [...$defaultColumns, ...$extraColumns[$table]];
            //Make sure all columns in matrix is really exist in the Prop list
            $availableColumnNames = array_values(array_map(fn ($prop) => $prop['column_name'], $props));
            $matrix[$table] = array_intersect($defaultColumns, $availableColumnNames);
            $diff = array_diff($defaultColumns, $matrix[$table]);
            if (sizeof($diff) > 0) $notFoundInProps[$table] = $diff;
        }
        $this->dump2("Column not found in prop.json", $notFoundInProps, __LINE__);
        $this->dump2("Matrix", $matrix, __LINE__);
        // dump($matrix);
        return $matrix;
    }

    private function renderListenDataSource($types)
    {
        $matrix = $this->getMatrix($types);
        // dump($matrix);
        $result = [];
        $columnsWithOracy = [];

        foreach ($matrix as $table => $columns) {
            // $modelPath = "App\\Models\\" . Str::singular($table);
            $modelPath = Str::modelPathFrom($table);
            $nameless = (new $modelPath)->nameless;
            $columnsWithoutOracy = array_filter($columns, fn ($column) => !str_contains($column, "()"));
            $columnsWithOracy[$table] = array_values(array_filter($columns, fn ($column) => str_contains($column, "()")));
            $rows = DB::table($table)->select($columnsWithoutOracy);
            if (!$nameless) $rows = $rows->orderBy('name');
            $objectRows = $rows->get()->toArray();
            $result[$table] = array_map(fn ($o) => (array)$o, $objectRows);
        }

        $this->dump2("columnsWithOracy", $columnsWithOracy, __LINE__);
        foreach ($columnsWithOracy as $table => $listOfFn) {
            // $modelPath = "App\\Models\\" . Str::singular($table);
            $modelPath = Str::modelPathFrom($table);
            if (sizeof($listOfFn) > 0) {
                foreach ($listOfFn as $fn) {
                    $fn_no_parenthesis = substr($fn, 0, strlen($fn) - 2); //Remove ()
                    foreach ($result[$table] as &$row) {
                        $model = $modelPath::find($row['id']);
                        $row[$fn] = $model->getCheckedByField($fn_no_parenthesis)->pluck('id')->toArray();
                    }
                }
            }
        }

        $this->dump2("Result", $result, __LINE__);
        return $result;
    }

    private function getListeners($types)
    {
        $output = [];
        foreach ($types as $type) {
            $sp = SuperProps::getFor($type);
            $result = array_values(Listeners::getAllOf($type));
            foreach ($result as &$line) {
                $relationships = $sp['props']["_" . $line['column_name']]['relationships'];
                unset($line['name']);
                if (!isset($relationships['table'])) {
                    $line['table_name'] = $line['column_name'] . " is not a HasDataSource control";
                } else {
                    $line['table_name'] = $relationships['table'];
                }
                $line['listen_to_tables'] = array_map(fn ($control) => $sp['props']["_" . $control]['relationships']['table'], $line['listen_to_fields']);
                // $line['filter_columns'] = $relationships['filter_columns'] ?? [];
                // $line['filter_values'] = $relationships['filter_values'] ?? [];
            }
            $output[$type] = $result;
        }

        return $output;
    }

    private function getFilters($types)
    {
        $output = [];
        foreach ($types as $type) {
            $sp = SuperProps::getFor($type);
            $result = [];
            foreach ($sp['props'] as $prop) {
                if (isset($prop['relationships']['filter_columns']) && sizeof($prop['relationships']['filter_columns']) > 0) {
                    $result[$prop['column_name']]['filter_columns'] = ($prop['relationships']['filter_columns']);
                    $result[$prop['column_name']]['filter_values'] = ($prop['relationships']['filter_values']);
                }
            }
            $output[$type] = $result;
        }
        // dump($output);
        return $output;
    }
}
