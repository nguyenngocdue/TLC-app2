<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\JsonControls;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait TraitEntityFieldHandler2
{
    use TraitEntityFormula;
    private function getProps1()
    {
        $table01Count = 0;
        $table01Index = "";
        $comment01Count = 0;
        $comment01Index = "";
        $result = [
            'oracy_prop' => [],
            'eloquent_prop' => [],
            'attachment' => [],
            'datetime' => [],
            'editable_table' => [],
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
            } elseif ($prop['control'] === 'comment') {
                $column_type = 'editable_table';
                $comment01Count++;
                $comment01Index = "comment" . str_pad($comment01Count, 2, '0', STR_PAD_LEFT);
            } else {
                switch ($prop['column_type']) {
                    case 'oracy_prop':
                    case 'eloquent_prop':
                        $column_type = $prop['column_type'];
                        break;
                    default:
                        $column_type = 'field';
                        break;
                }
            }
            if ($prop['control'] === 'relationship_renderer') {
                $result[$column_type][$table01Index] = $prop['name'];
            } elseif ($prop['control'] === 'comment') {
                $result[$column_type][$comment01Index] = $prop['name'];
            } elseif (in_array($prop['control'], $dateTimeControls)) {
                $result[$column_type][$control_sub_type][] = $prop['name'];
            } else {
                $result[$column_type][] = $prop['name'];
            }
        }
        //This is for Inspection Checklist Sheet screen
        if ($this->superProps['type'] == 'qaqc_insp_chklst_sht') {
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
                $dataSource[$column_name] = isset($dataSource[$column_name]);
            }
        }
        return $dataSource;
    }

    private function handleTextArea($dataSource)
    {
        foreach ($this->superProps['props'] as $prop) {
            if ($prop['control'] === 'textarea' && $prop['column_type'] === 'json') {
                $column_name = $prop['column_name'];
                $text = $dataSource[$column_name];
                $dataSource[$column_name] = /*json_decode*/ (preg_replace("/\r|\n/", "", $text));
            }
        }
        return $dataSource;
    }

    private function handleStatus($theRow, $newStatus)
    {
        if (!$newStatus) return;
        $theRow->transitionTo($newStatus);
    }

    private function handleCheckboxAndDropdownMulti(Request $request, $theRow, array $oracyProps)
    {
        foreach ($oracyProps as $prop) {
            $relatedModel = $this->superProps['props'][$prop]['relationships']['oracyParams'][1];
            $propName = substr($prop, 1); //Remove first "_"
            $ids = $request->input($propName);
            if (is_null($ids)) $ids = []; // Make sure it sync when unchecked all
            $ids = array_map(fn ($id) => $id * 1, $ids);

            $fieldName = substr($propName, 0, strlen($propName) - 2); //Remove parenthesis ()
            $theRow->syncCheck($fieldName, $relatedModel, $ids);
            $this->dump1("handleCheckboxAndDropdownMulti $propName", $ids, __LINE__);
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
        $dataSource = $this->handleTextArea($dataSource);
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
                Toastr::error($toastrMessage, "$table01Name $action failed");
            }
        } else {
            Toastr::success("$this->type $action successfully", "$action $this->type");
        }
    }

    private function postValidationForDateTime(Request &$request, $props)
    {
        $newRequest = $request->input();
        $dateTimeProps = $props['datetime'];
        foreach ($dateTimeProps as $subType => $controls) {
            foreach ($controls as $control) {
                $propName = substr($control, 1); //Remove first "_"
                if (in_array($subType, JsonControls::getDateTimeControls())) {
                    if (isset($newRequest[$propName])) {
                        // dump($subType, $propName, $newRequest[$propName]);
                        $newRequest[$propName] = DateTimeConcern::convertForSaving($subType, $newRequest[$propName]);
                    }
                }
            }
        }
        $request->replace($newRequest);
    }

    private function addEntityType($array, $key, $value)
    {
        $array[$key] = $value;
        return $array;
    }

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
        $comments = $this->superProps['comments'];
        foreach ($comments as $comment01Name => $commentFieldName) {
            $alright = true;
            $comment = $request->input($comment01Name);
            if ($comment) {
                $contents = $comment['content'];
                foreach ($contents as $content) {
                    if ($content == "") {
                        $alright  = false;
                        break;
                    }
                }
            }
            // dump($comment);
            $field = substr($commentFieldName, 1); //Remove first _
            if ($alright) $request->request->add([$field => 'has at least one line']);
        }
        // dump($alright);
        // dd();
    }
}
