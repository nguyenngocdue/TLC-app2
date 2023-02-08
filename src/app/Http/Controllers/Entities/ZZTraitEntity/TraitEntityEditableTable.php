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
        // echo "RECURSIVE = RECURSIVE = RECURSIVE = RECURSIVE = RECURSIVE = ";
        // dump($props);
        // dump($this->superProps['props']);

        foreach ($props as $propName) {
            $tableName = $this->superProps['props'][$propName]['relationships']['table'];
            $tableType = $this->superProps['props'][$propName]['relationships']['type'];
            $dataSource = $this->stripDataSource($request, $tableName);
            $this->dump1("RECURSIVE CALLED STRIPPING $tableName", $dataSource, __LINE__);
            if (is_null($dataSource)) continue;

            $dataSource = $this->parseHTTPArrayToLines($dataSource);
            $this->dump1("RECURSIVE CALLED PARSING $tableName", $dataSource, __LINE__);
            // dump($dataSource);
            foreach ($dataSource as $line) {
                $fakeRequest = new Request();
                $line['tableNames'] = "fakeRequest";
                $line['tableName'] = $tableName;
                $fakeRequest->merge($line);
                // dd($fakeRequest);
                // dump($line);

                $controllerPath = "App\\Http\\Controllers\\Entities\\$tableType\\EntityCRUDController";
                $controller = new $controllerPath;
                if ($line['id']) {
                    // dump("Recursive called for update to $tableName for #" . $line['id']);
                    // dump($line);
                    $controller->update($fakeRequest, $line['id']);
                    // $this->update($fakeRequest, $line['id']);
                } else {
                    // dump("Recursive called for store to $tableName");
                    // dd("STORING STORING STORING STORING");
                    $controller->store($fakeRequest);
                    // $this->store($fakeRequest);
                }
            }
        }
    }
}
