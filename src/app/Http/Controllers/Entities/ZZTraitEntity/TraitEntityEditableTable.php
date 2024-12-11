<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Entities\EntityCRUDController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\FileBag;

trait TraitEntityEditableTable
{
    use TraitEntityEditableTableFooter;

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

    private function distributeFileBag(Request $request, $rowIndex, Request $fakeRequest)
    {
        $fileBag = $request->files->get('table01');
        $fileBag = $fileBag ? $fileBag : [];
        $array = [];
        foreach ($fileBag as $column_name => $file) {
            // dump($file);
            // dump($rowIndex);
            if (isset($file[$rowIndex])) {
                $array[$column_name] = $file[$rowIndex];
            }
        }
        // dump($array);
        if (!empty($array)) $fakeRequest->files = new FileBag($array);
        // dump($fakeRequest->files);
    }

    private function handleEditableTables(Request $request, $props, $getManyLineParams, $parentId)
    {
        dd($request->input());
        $toastrResult = [];
        $lineResult = [];
        $table01Names = $request['tableNames'];
        $tableFnNames = $request['tableFnNames'];
        session()->forget('editableTablesTransactions');

        $toBeOverrideAggregatedFields = [];
        if (!is_null($table01Names)) {
            foreach ($table01Names as $table01Name => $tableName) {
                $dataSource = $request[$table01Name];
                $this->dump1("RECURSIVE CALLED STRIPPING $tableName", $dataSource, __LINE__);
                if (is_null($dataSource)) continue;

                $dataSource = $this->parseHTTPArrayToLines($dataSource);
                // dump($tableName);
                // if ($tableName === 'comments') {
                //     $dataSource = $this->removeNullCommentOfNullId($dataSource);
                // }

                $this->dump1("RECURSIVE CALLED PARSING from HTML DATA to ARRAY $tableName", $dataSource, __LINE__);
                // dump($dataSource);
                // session()->flush();
                session()->put('editableTables_index', $table01Names);

                foreach ($dataSource as $rowIndex => $line) {
                    $fakeRequest = new Request();
                    // if ($tableName === 'comments') $line['commentable_id'] = $parentId;
                    $line['tableNames'] = "fakeRequest";
                    // $line['rowIndex'] = $rowIndex;
                    if (isset($props[$table01Name])) { //<< if we hide the table in Manage Props
                        $line['idForScroll'] = substr($props[$table01Name], 1); //remove first "_"
                    }
                    // dump($line);
                    $fakeRequest->merge($line);
                    $this->distributeFileBag($request, $rowIndex, $fakeRequest);

                    $controller = new EntityCRUDController();
                    $controller->init($tableName);
                    // dump($line);
                    if (isset($line['id']) && !is_null($line['id'])) {
                        if (isset($line['DESTROY_THIS_LINE']) && ('true' == $line["DESTROY_THIS_LINE"])) {
                            // dump("Delete");
                            $destroyRequest = new Request(['ids' => $line['id']]);
                            $destroySuccess = $controller->destroyMultiple($destroyRequest, true);
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
                            // dump("Update");
                            $getManyLineParams1 = null;
                            if ($tableFnNames && $tableFnNames[$table01Name]) {
                                $eloquentFnName = $tableFnNames[$table01Name];
                                $getManyLineParams1 = $getManyLineParams[$eloquentFnName];
                            }
                            // Log::info($eloquentFnName);
                            $updatedId = $controller->update($fakeRequest, $line['id'], $getManyLineParams1);
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
                            //Ignore this case, as it contains no code
                        } else {
                            // dump("Insert");
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

                $toBeOverrideAggregatedFields = $this->recalculateAggregatedFields($tableName, $table01Name, $tableFnNames, $parentId);
                // Log::info($toBeOverrideAggregatedFields);
            }
        }
        // return $toastrResult;
        // dump($lineResult);
        $result = [$toastrResult, empty($lineResult), $toBeOverrideAggregatedFields];
        // dump($result);
        return $result;
    }
}
