<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use Illuminate\Support\Facades\Log;

trait TraitListenerControl
{
    use TraitEntityListenDataSource;
    use TraitGetSuffixListenerControl;
    private function renderJSForK()
    {
        $tableName = $this->tableName;
        $k = [$tableName => $this->getDataSource(),];
        $str = "\n";
        $str .= "<script>";
        $str .= " k = {...k, ..." . json_encode($k) . "};";
        $str .= "</script>";
        $str .= "\n";
        echo $str;
        $this->renderJSForListener();
    }

    //Public for Sidebar Filter Subproject
    //Suffix is for case one form have multiple dropdown but same control (like sub projects in sidebar filter and sub projects in edit modal)
    public function getListenersOfDropdown2()
    {
        $a = $this->getListeners2($this->typeToLoadListener);
        // dump($a);
        $columnName = $this->id ?? $this->name;
        // dump($columnName);
        $suffix = $this->getSuffix();
        // dump($suffix);
        $a = array_filter($a, fn ($x) => ($x['column_name'] . $suffix) == $columnName);
        // dump($a);
        $a = array_values($a);
        // dump($a);
        if (!isset($a[0])) {
            // dump("A");
            // throw new \Exception("Can not find control with column_name as [" . $columnName . "], maybe you forget getSuffix() function.");
            //<<This cause WIR View All Matrix crashes
        } else {
            // dump("B");
            $a = $a[0];

            if ($suffix) {
                $a['column_name'] .= $suffix;
                foreach ($a['triggers'] as &$x) $x .= $suffix;
                // foreach ($a['listen_to_fields'] as $x) $x .= $suffix;
            }
            // Log::info($a);
            $listenersOfDropdown2 = [$a];
            return $listenersOfDropdown2;
        }
    }

    private function renderJSForListener()
    {
        if (isset($this->typeToLoadListener) && !is_null($this->typeToLoadListener)) {
            $listenersOfDropdown2 = $this->getListenersOfDropdown2();

            if (is_array($listenersOfDropdown2)) {
                $str = "\n";
                $str .= "<script>";
                $str .= " listenersOfDropdown2 = [...listenersOfDropdown2, ..." . json_encode($listenersOfDropdown2) . "];";
                $str .= "</script>";
                $str .= "\n";
                echo $str;
            }
        }
    }

    private function getParamsForHasDataSource()
    {
        switch ($this->control) {
            case "radio-or-checkbox2":
                $classList = ClassList::RADIO_CHECKBOX;
                break;
            case "dropdown2":
                $classList = ClassList::DROPDOWN;
                break;
            default:
                $classList = "border border-gray-200 rounded (Unknown-classList-of-$this->control)";
                break;
        }
        return  [
            'id' => $this->id ?? $this->name,
            'name' => $this->name,
            'selected' => $this->selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $this->tableName,
            'readOnly' => $this->readOnly,
            'classList' => $classList,
            'multiple' => $this->multiple ? true : false,
            'allowClear' => $this->allowClear,
        ];
    }
}
