<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait TraitEntityEditableTable
{
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
        // dump($this->superProps['props']);
        // dump($props);
        $table01Names = $request['tableNames'];
        foreach ($table01Names as $table01Name => $tableName) {
            $tableType = ucfirst(Str::singular($tableName));
            $dataSource = $request[$table01Name];
            $this->dump1("RECURSIVE CALLED STRIPPING $tableName", $dataSource, __LINE__);
            if (is_null($dataSource)) continue;

            $dataSource = $this->parseHTTPArrayToLines($dataSource);
            $this->dump1("RECURSIVE CALLED PARSING from HTML DATA to ARRAY $tableName", $dataSource, __LINE__);
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
                        // dump("Updating line " . $line['id'] . " of table $tableType");
                        // dump($line);
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
