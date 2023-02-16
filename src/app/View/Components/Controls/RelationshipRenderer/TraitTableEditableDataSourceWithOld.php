<?php

namespace App\View\Components\Controls\RelationshipRenderer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;

trait TraitTableEditableDataSourceWithOld
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

    private function convertOldToDataSource($table01Name, $dataSource, $lineModelPath)
    {
        $old = old($table01Name);
        // dump($dataSource);
        if (is_null($old)) return $dataSource;
        $oldObjects = $this->parseHTTPArrayToLines($old);
        $result = [];
        $editableTablesTransactions = session()->get('editableTablesTransactions');
        $editableTablesTransactions = $editableTablesTransactions[$table01Name];
        dump($editableTablesTransactions);
        // dump($oldObjects);
        foreach ($oldObjects as $oldObject) {
            $newObject = new $lineModelPath($oldObject);
            $result[] = $newObject;
        }
        // dump($result);
        $result = new Collection($result);
        // dump($result);
        return $result;
    }

    private function remakeOrderNoColumn($dataSource)
    {
        // dump($dataSource);
        foreach ($dataSource as $index => &$row) {
            $row->order_no = 1000 + $index * 10;
        }
        return $dataSource;
    }

    private function alertIfFieldsAreMissingFromFillable(object $instance, string $lineModelPath, array $editableColumns)
    {
        $columns = array_filter($editableColumns, fn ($column) => isset($column['editable']) && $column['editable'] == true);
        $columns = array_filter($columns, fn ($column) => !str_contains($column['dataIndex'], "()")); //<< Remove all oracy params
        $columns = array_values(array_map(fn ($column) => $column['dataIndex'], $columns));
        // dump($editableColumns);
        // dump($columns);
        $fillable = $instance->getFillable();
        $diff = array_diff($columns, $fillable);
        if (!empty($diff)) {
            $msg = join(", ", $diff) . " be missing from fillable of $lineModelPath.";
            echo Blade::render("<x-feedback.alert type='error' message='$msg' ></x-feedback.alert>");
        }
    }

    private function attachActionColumn($table01Name, $dataSource, $isOrderable)
    {
        // dump($dataSource);
        foreach ($dataSource as &$row) {
            $id = $row->order_no;
            // dump($index);
            $type = $this->tableDebug ? "text" : "hidden";
            $output = "
            <input readonly name='{$table01Name}[finger_print][]' value='$id' type=$type class='w-10 bg-gray-300' />
            <input readonly name='{$table01Name}[DESTROY_THIS_LINE][]'  type=$type class='w-10 bg-gray-300' />
            <div class='whitespace-nowrap flex justify-center '>";
            if ($isOrderable) $output .= "<x-renderer.button size='xs' value='$table01Name' onClick='moveUpEditableTable({control:this, fingerPrint: $id})'><i class='fa fa-arrow-up'></i></x-renderer.button>
                 <x-renderer.button size='xs' value='$table01Name' onClick='moveDownEditableTable({control:this, fingerPrint: $id})'><i class='fa fa-arrow-down'></i></x-renderer.button>";
            $output .= "<x-renderer.button size='xs' value='$table01Name' onClick='duplicateEditableTable({control:this, fingerPrint: $id})' type='secondary' ><i class='fa fa-copy'></i></x-renderer.button>
                <x-renderer.button size='xs' value='$table01Name' onClick='trashEditableTable({control:this, fingerPrint: $id})' type='danger' ><i class='fa fa-trash'></i></x-renderer.button>
            </div>
            ";
            $row->action = Blade::render($output);
        }
        return $dataSource;
    }
}
