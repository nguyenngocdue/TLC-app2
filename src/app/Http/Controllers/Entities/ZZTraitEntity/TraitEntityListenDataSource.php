<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\DBTable;
use App\Utils\Support\Json\Listeners;
use Illuminate\Support\Arr;
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
            $columns = DBTable::getColumnNames($table);

            $defaultColumns =  ['id', 'name', 'description'];
            if (isset($extraColumns[$table])) $defaultColumns = [...$defaultColumns, ...$extraColumns[$table]];
            $matrix[$table] = array_intersect($defaultColumns, $columns);
            $diff = array_diff($matrix[$table], $defaultColumns);
            if (sizeof($diff) > 0) $this->dump2("Column not found in $table", $diff);
        }
        $this->dump2("Result", $matrix);

        $result = [];
        foreach ($matrix as $table => $columns) {
            $result[$table] = DB::table($table)
                ->select($columns)
                ->orderBy('name')
                ->get();
        }

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
