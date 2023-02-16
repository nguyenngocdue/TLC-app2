<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $table01Names = $request['tableNames'];
        session()->forget('editableTablesTransactions');
        foreach ($table01Names as $table01Name => $tableName) {
            $tableType = ucfirst(Str::singular($tableName));
            $dataSource = $request[$table01Name];
            $this->dump1("RECURSIVE CALLED STRIPPING $tableName", $dataSource, __LINE__);
            if (is_null($dataSource)) continue;

            $dataSource = $this->parseHTTPArrayToLines($dataSource);
            $this->dump1("RECURSIVE CALLED PARSING from HTML DATA to ARRAY $tableName", $dataSource, __LINE__);
            // dump($dataSource);
            // session()->flush();
            session()->put('editableTables_index', $table01Names);

            foreach ($dataSource as $line) {
                $fakeRequest = new Request();
                $line['tableNames'] = "fakeRequest";
                $line['idForScroll'] = substr($props[$table01Name], 1); //remove first "_"
                // dump($line);
                $fakeRequest->merge($line);
                $controllerPath = "App\\Http\\Controllers\\Entities\\$tableType\\EntityCRUDController";
                $controller = new $controllerPath;
                if (isset($line['id']) && !is_null($line['id'])) {
                    if (isset($line['DESTROY_THIS_LINE']) && !is_null($line["DESTROY_THIS_LINE"])) {
                        // dd("Destroying", $line['id']);
                        $controller->destroy($fakeRequest, $line['id']);
                        //Not necessary because it will be deleted when mapping with the next lines
                        session()->push('editableTablesTransactions.' . $table01Name, ["msg" => "Destroyed", 'id' => 1 * $line['id'],]);
                        // Log::info("Destroyed $table01Name " . $line['id']);
                    } else {
                        // dump("Updating line $table01Name " . $line['id'] . " of table $tableType");
                        // dump($line);
                        $controller->update($fakeRequest, $line['id']);
                        session()->push('editableTablesTransactions.' . $table01Name, ["msg" => "Updated", 'id' => 1 * $line['id'],]);
                        // Log::info("Updated $table01Name " . $line['id']);
                    }
                } else {
                    if (isset($line['DESTROY_THIS_LINE']) && !is_null($line["DESTROY_THIS_LINE"])) {
                        //Ignore this case
                    } else {
                        // dump($fakeRequest);
                        $insertedId = $controller->store($fakeRequest);
                        //Incase the storing failed, it will return a HTML string of 302
                        if (is_numeric($insertedId)) {
                            session()->push('editableTablesTransactions.' . $table01Name, ["msg" => "Created", 'id' => 1 * $insertedId,]);
                            // Log::info("Created $table01Name " . $line['id']);
                        } else {
                            session()->push('editableTablesTransactions.' . $table01Name, ["msg" => "insert_failed_due_to_validation", 'id' => null]);
                            // Log::info("insert_failed_due_to_validation $table01Name " . $line['id']);
                        }
                    }
                }
            }
        }
    }
}
