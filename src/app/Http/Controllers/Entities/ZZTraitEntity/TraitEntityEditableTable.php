<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Entities\EntityCRUDController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitEntityEditableTable
{
    use TraitEntityComment2;

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

    private function handleEditableTables(Request $request, $props, $parentId)
    {
        // dump($request);
        $toastrResult = [];
        $lineResult = [];
        $table01Names = $request['tableNames'];
        session()->forget('editableTablesTransactions');
        foreach ($table01Names as $table01Name => $tableName) {
            $dataSource = $request[$table01Name];
            $this->dump1("RECURSIVE CALLED STRIPPING $tableName", $dataSource, __LINE__);
            if (is_null($dataSource)) continue;

            $dataSource = $this->parseHTTPArrayToLines($dataSource);
            // dump($tableName);
            if ($tableName === 'comments') {
                $dataSource = $this->removeNullCommentOfNullId($dataSource);
            }

            $this->dump1("RECURSIVE CALLED PARSING from HTML DATA to ARRAY $tableName", $dataSource, __LINE__);
            // dump($dataSource);
            // session()->flush();
            session()->put('editableTables_index', $table01Names);

            foreach ($dataSource as $line) {
                $fakeRequest = new Request();
                if ($tableName === 'comments') $line['commentable_id'] = $parentId;
                $line['tableNames'] = "fakeRequest";
                $line['idForScroll'] = substr($props[$table01Name], 1); //remove first "_"
                // dump($line);
                $fakeRequest->merge($line);

                $controller = new EntityCRUDController();
                $controller->init($tableName);
                if (isset($line['id']) && !is_null($line['id'])) {
                    if (isset($line['DESTROY_THIS_LINE']) && ('true' == $line["DESTROY_THIS_LINE"])) {
                        $destroySuccess = $controller->destroy($line['id']);
                        // $destroySuccess = $controller->destroy($fakeRequest, $line['id']);
                        //Not necessary because it will be deleted when mapping with the next lines
                        if ($destroySuccess) {
                            session()->push('editableTablesTransactions.' . $table01Name, ["result" => 1, "msg" => "Destroyed", 'id' => 1 * $line['id'],]);
                        } else {
                            session()->push('editableTablesTransactions.' . $table01Name, ["result" => 0, "msg" => "destroy_failed", 'id' => 1 * $line['id'],]);
                            $toastrResult[$table01Name] = "Delete line failed.";
                            $lineResult[$table01Name] = false;
                        }
                    } else {
                        $updatedId = $controller->update($fakeRequest, $line['id']);
                        if (is_numeric($updatedId)) {
                            session()->push('editableTablesTransactions.' . $table01Name, ["result" => 1, "msg" => "Updated", 'id' => 1 * $line['id'],]);
                        } else {
                            session()->push('editableTablesTransactions.' . $table01Name, ["result" => 0, "msg" => "update_failed_due_to_validation", 'id' => 1 * $line['id'],]);
                            $toastrResult[$table01Name] = "Update line failed.";
                            $lineResult[$table01Name] = false;
                        }
                    }
                } else {
                    if (isset($line['DESTROY_THIS_LINE']) && $line["DESTROY_THIS_LINE"] == true) {
                        //Ignore this case
                    } else {
                        $insertedId = $controller->store($fakeRequest);
                        //Incase the storing failed, it will return a HTML string of 302
                        if (is_numeric($insertedId)) {
                            session()->push('editableTablesTransactions.' . $table01Name, ["result" => 1, "msg" => "Created", 'id' => 1 * $insertedId,]);
                        } else {
                            session()->push('editableTablesTransactions.' . $table01Name, ["result" => 0, "msg" => "insert_failed_due_to_validation", 'id' => null]);
                            $toastrResult[$table01Name] = "Create line failed.";
                            $lineResult[$table01Name] = false;
                        }
                    }
                }
            }
        }
        // return $toastrResult;
        // dump($lineResult);
        $result = [$toastrResult, empty($lineResult)];
        // dump($result);
        return $result;
    }
}
