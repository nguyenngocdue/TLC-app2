<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\JsonControls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait TraitEntityFieldHandler2
{
    use TraitEntityFormula;
    private function getProps1($item)
    {
        $table01Count = 0;
        $table01Index = "";
        // $comment01Count = 0;
        // $comment01Index = "";
        $result = [
            'hasOne' => [],
            'hasMany' => [],
            'hasManyThrough' => [],
            'belongsTo' => [],
            'belongsToMany' => [],
            'morphTo' => [],
            'morphMany' => [],
            'morphOne' => [],
            'morphToMany' => [],

            'attachment' => [],
            'datetime' => [],
            'editable_table' => [],
            'editable_table_get_many_line_params' => [],
        ];

        $dateTimeControls = JsonControls::getDateTimeControls();
        foreach ($this->superProps['props'] as $prop) {
            if ($prop['hidden_edit']) continue;
            if (in_array($prop['control'], $dateTimeControls)) {
                $column_type = 'datetime';
                $control_sub_type = $prop['control'];
            } elseif ($prop['control'] === 'attachment') {
                $column_type = 'attachment';
            } elseif ($prop['control'] === 'relationship_renderer') {
                $column_type = 'editable_table';
                $table01Count++;
                $table01Index = "table" . str_pad($table01Count, 2, '0', STR_PAD_LEFT);
                // } elseif ($prop['control'] === 'comment') {
                //     $column_type = 'editable_table';
                //     $comment01Count++;
                //     $comment01Index = "comment" . str_pad($comment01Count, 2, '0', STR_PAD_LEFT);
            } else {
                $relationships = ['hasOne', 'hasMany', 'hasManyThrough', 'belongsTo', 'belongsToMany', 'morphTo', 'morphMany', 'morphOne', 'morphToMany',];
                $column_type = 'field';
                if (in_array($prop['column_type'], $relationships)) $column_type = $prop['column_type'];
            }
            if ($prop['control'] === 'relationship_renderer') {
                $result[$column_type][$table01Index] = $prop['name'];
                // } elseif ($prop['control'] === 'comment') {
                //     $result[$column_type][$comment01Index] = $prop['name'];
            } elseif (in_array($prop['control'], $dateTimeControls)) {
                $result[$column_type][$control_sub_type][] = $prop['name'];
            } else {
                $result[$column_type][] = $prop['name'];
            }
        }

        foreach ($this->superProps['props'] as $prop) {
            // Log::info($prop['relationships']);
            $control_name = $prop['relationships']['control_name'] ?? false;
            // Log::info($control_name);
            if ($control_name) {
                $modelPath = Str::modelPathFrom(Str::plural($this->type));
                if (isset($modelPath::$eloquentParams[$control_name])) {
                    // Log::info($modelPath::$eloquentParams[$control_name][1]);
                    $modelPathOfLine = ($modelPath::$eloquentParams[$control_name][1]);
                    $fn = ($prop['relationships']['renderer_edit_param'] ?? false);

                    if ($fn) {
                        $obj = (new $modelPathOfLine);
                        if (method_exists($obj, $fn)) {
                            $getManyLineParams = $obj->$fn($item);
                            $result['editable_table_get_many_line_params'][$control_name] = $getManyLineParams;
                        }
                    }
                }
            }
        }

        //This is for Inspection Checklist Sheet screen
        $arrayCheck = ['qaqc_insp_chklst_sht', 'hse_insp_chklst_sht'];
        if (in_array($this->superProps['type'], $arrayCheck)) {
            $result['editable_table'] = [
                'table01' => '_getLines',
            ];
        }
        $this->dump1("getProps1", $result, __LINE__);
        return $result;
    }

    private function handleToggle($dataSource)
    {
        foreach ($this->superProps['props'] as $prop) {
            if ($prop['control'] === 'toggle') {
                $column_name = $prop['column_name'];
                $value = isset($dataSource[$column_name]) && $dataSource[$column_name];
                $dataSource[$column_name] = $value;
            }
        }
        return $dataSource;
    }

    private function handleTextArea($dataSource, $action)
    {
        foreach ($this->superProps['props'] as $prop) {
            if ($prop['control'] === 'textarea' && $prop['column_type'] === 'json') {
                $column_name = $prop['column_name'];
                $text = $dataSource[$column_name];
                //<<json_decode here is not required in store as Array to Str exception will be thrown
                //<<json_decode here is required in update, because in create-edit screen, 
                //<<the empty array [] will be reckon as "[]", and therefore the user will not be able to load/save userSettings.

                $value = preg_replace("/\r|\n/", "", $text);
                $value = ($action == 'store') ? $value : json_decode($value);
                $dataSource[$column_name] = $value;
            }
        }
        return $dataSource;
    }

    private function handleStatus($theRow, $request, $newStatus)
    {
        if (!$newStatus) return;

        // Log::info($newStatus . " " . $this->table . " " . $this->ncr_all_closed);
        $input = $request->input();
        if ($this->type == 'qaqc_wir') {
            if ($newStatus == 'closed') {
                $value = $input['ncr_status_unique_value'];
                if (!in_array($value, ['"closed"', '""'])) {
                    // $str = $this->ncr_status_unique_value . " " . $this->ncr_all_closed;
                    throw ValidationException::withMessages([
                        "a" => "You cannot close this document as it has pending NCRs.<br/>Current value: $value.",
                    ]);
                }
            }
        }

        $theRow->transitionTo($newStatus);
    }

    private function handleCheckboxAndDropdownMulti2a(Request $request, $theRow, array $eloquentProps, array $getManyLineParams = null)
    {
        $uid = CurrentUser::id();
        //
        $propNames = [];
        foreach ($eloquentProps as $possiblyM2MProps) {
            $propName = substr($possiblyM2MProps, 1); //Remove first "_"
            $propNames[] = $propName;
        }
        if (!is_null($getManyLineParams)) {
            $getManyLineParamsDataIndex = array_map(fn ($i) => $i['dataIndex'], $getManyLineParams);
            $propNames = array_filter($propNames, fn ($i) => in_array($i, $getManyLineParamsDataIndex));
        }
        foreach ($propNames as $propName) {
            $values = $request->input($propName);
            $theRow->{$propName}()->syncWithPivotValues($values, ['owner_id' => $uid]);
        }
    }

    // private function handleCheckboxAndDropdownMulti(Request $request, $theRow, array $oracyProps, array $getManyLineParams = null)
    // {
    //     // Log::info($this->type);
    //     $propNames = [];
    //     foreach ($oracyProps as $prop) {
    //         $propName = substr($prop, 1); //Remove first "_"
    //         $propNames[] = $propName;
    //     }
    //     // Log::info("PropNames to be modify m2m: " . join(", ", $propNames));

    //     if (!is_null($getManyLineParams)) {
    //         // Log::info("Filter oracy props according to getManyLineParams");
    //         // Log::info($oracyProps);
    //         // Log::info($getManyLineParams);
    //         $getManyLineParamsDataIndex = array_map(fn ($i) => $i['dataIndex'], $getManyLineParams);
    //         // Log::info($getManyLineParamsDataIndex);
    //         $propNames = array_filter($propNames, fn ($i) => in_array($i, $getManyLineParamsDataIndex));
    //     }
    //     // Log::info("PropNames to be updated: " . join(", ", $propNames));

    //     foreach ($propNames as $propName) {
    //         $relatedModel = $this->superProps['props']['_' . $propName]['relationships']['oracyParams'][1];
    //         $ids = $request->input($propName);
    //         if (is_null($ids)) $ids = []; // Make sure it sync when unchecked all
    //         $ids = array_map(fn ($id) => $id * 1, $ids);

    //         $fieldName = substr($propName, 0, strlen($propName) - 2); //Remove parenthesis ()
    //         $theRow->syncCheck($fieldName, $relatedModel, $ids);
    //         $this->dump1("handleCheckboxAndDropdownMulti $propName", $ids, __LINE__);
    //     }
    // }

    private function removeAttachmentForFields(&$fields, $keyRemoves, $isFakeRequest, $allTable01Names)
    {
        foreach ($keyRemoves as $key) {
            $key = substr($key, 1);
            if (isset($fields[$key])) unset($fields[$key]);
        }
        if (!$isFakeRequest) {
            foreach ($allTable01Names as $tableName) {
                unset($fields[$tableName]);
            }
        }
    }

    private function handleFields(Request $request, $action)
    {
        $status = $request->status ?? '';
        $dataSource = $request->except(['_token', '_method', 'status', 'created_at', 'updated_at']);
        if ($action === 'store') $dataSource['id'] = null;
        $this->dump1("Before handleFields", $dataSource, __LINE__);
        $dataSource = $this->applyFormula($dataSource, $action, $status);
        $dataSource = $this->handleToggle($dataSource);
        $dataSource = $this->handleTextArea($dataSource, $action);
        $this->dump1("After handleFields", $dataSource, __LINE__);
        return $dataSource;
    }

    protected function handleMyException($e, $action, $phase)
    {
        dump("Exception during $action phase $phase " . $e->getFile() . " line " . $e->getLine());
        dd($e->getMessage());
        // dd($e);
    }

    private function handleToastrMessage($action, $toastrResult)
    {
        if (!empty($toastrResult)) {
            foreach ($toastrResult as $table01Name => $toastrMessage) {
                toastr()->error($toastrMessage, "$table01Name $action failed");
            }
        } else {
            $app = LibApps::getFor($this->type);
            $title = $app['title'];
            $action = $action . "d";
            toastr()->success("This document is $action successfully.", Str::ucfirst($action) . " $title");
        }
    }

    // private function postValidationForDateTime(Request &$request, $props)
    // {
    //     $newRequest = $request->input();
    //     $dateTimeProps = $props['datetime'];
    //     foreach ($dateTimeProps as $subType => $controls) {
    //         foreach ($controls as $control) {
    //             $propName = substr($control, 1); //Remove first "_"
    //             if (in_array($subType, JsonControls::getDateTimeControls())) {
    //                 if (isset($newRequest[$propName])) {
    //                     // dump($subType, $propName, $newRequest[$propName]);
    //                     $newRequest[$propName] = DateTimeConcern::convertForSaving($subType, $newRequest[$propName]);
    //                 }
    //             }
    //         }
    //     }
    //     $request->replace($newRequest);
    // }

    private function makeUpTableFieldForRequired(Request $request)
    {
        $tableNames = $request->input('tableNames');
        array_shift($tableNames); //Remove table00 (the_form)
        $sp = $this->superProps;
        foreach ($tableNames as $table01Name => $tableName) {
            if ($request->input($table01Name)) { //User has submit at least one line
                if (isset($sp['tables'][$tableName])) {
                    $functions = $sp['tables'][$tableName];
                    foreach ($functions as $function) {
                        $request->request->add([$function => 'has at least one line']);
                    }
                }
            }
        }
    }

    private function makeUpAttachmentFieldForRequired($item, Request $request)
    {
        $attachments = $this->superProps['attachments'];
        // dump($attachments);
        foreach ($attachments as $attachment) {
            $function = substr($attachment, 1); //Remove leading underscore
            $files = $item->{$function};
            if ($files) {
                $count = sizeof($files);
                if ($count)  $request->request->add([$function => $count . " item_xyz"]);
                else $request->request->remove($function);
            } else {
                $request->request->remove($function);
            }
        }
        // dump($item);
    }

    private function makeUpCommentFieldForRequired(Request $request)
    {
        $comments = $request->input('comments');
        if (is_null($comments)) return;

        $result = [];
        foreach ($comments as $comment) {
            $result[$comment['category_name']] = 0;
        }

        foreach ($comments as  $comment) {
            if (is_null($comment['id']) && is_null($comment['content'])) continue;
            if (isset($comment['toBeDeleted']) && $comment['toBeDeleted'] !== 'false') continue;
            // dump($comment);
            $result[$comment['category_name']]++;
        }
        // dump($result);

        $result = array_filter($result, fn ($i) => $i);
        // dump($result);

        foreach ($result as $fieldName => $count) {
            // dump($fieldName);
            $request->request->add([$fieldName => "has $count line(s)"]);
        }
    }
}
