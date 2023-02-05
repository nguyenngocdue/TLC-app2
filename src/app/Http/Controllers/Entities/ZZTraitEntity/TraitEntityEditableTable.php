<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Http\Request;

trait TraitEntityEditableTable
{
    private function stripDataSource(Request $request, $tableName)
    {
        $tableNames = $request['tableNames'];
        $table00Name = '';
        foreach ($tableNames as $key => $value) {
            if ($value === $tableName) {
                $table00Name = $key;
                break;
            }
        }

        $dataSource = $request[$table00Name];
        // dump($table00Name);
        // dump($dataSource);
        return $dataSource;
    }

    private function parseHTTPArrayToLines(array $dataSource)
    {
        $result = [];
        foreach ($dataSource as $fieldName => $fieldValueArray) {
            foreach ($fieldValueArray as $key => $value) {
                $result[$key][$fieldName] = $value;
            }
        }
        return $result;
    }

    private function handleEditableTables(Request $request, $props)
    {
        // dump($request);
        // dump($props);
        // echo "RECURSIVE = RECURSIVE = RECURSIVE = RECURSIVE = RECURSIVE = ";
        foreach ($props as $propName) {
            $tableName = $this->superProps['props'][$propName]['relationships']['table'];
            $dataSource = $this->stripDataSource($request, $tableName);

            $dataSource = $this->parseHTTPArrayToLines($dataSource);
            // dump($dataSource);
            foreach ($dataSource as $line) {
                $fakeRequest = new Request();
                $line['tableNames'] = "fakeRequest";
                $line['tableName'] = $tableName;
                $fakeRequest->merge($line);
                // dd($fakeRequest);
                // dump($line);
                if ($line['id']) {
                    $this->update($fakeRequest, $line['id']);
                } else {
                    dd("STORING STORING STORING STORING");
                    $this->store($fakeRequest);
                }
            }
        }
    }
}
