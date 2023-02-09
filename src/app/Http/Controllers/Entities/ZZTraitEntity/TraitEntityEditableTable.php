<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Http\Request;

trait TraitEntityEditableTable
{
    private function stripDataSource(Request $request, $tableName)
    {
        $tableNames = $request['tableNames'];
        $table00Name = '';
        if (is_null($tableNames))  return [];
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
            // dd($dataSource);

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
                    if (isset($line['DESTROY_THIS_LINE']) && !is_null($line["DESTROY_THIS_LINE"])) {
                        dd("Destroying", $line['id']);
                        $controller->destroy($fakeRequest, $line['id']);
                    } else {
                        $controller->update($fakeRequest, $line['id']);
                    }
                } else {
                    if (isset($line['DESTROY_THIS_LINE']) && !is_null($line["DESTROY_THIS_LINE"])) {
                        //Ignore this case
                    } else {
                        $controller->store($fakeRequest);
                    }
                }
            }
        }
    }
}
