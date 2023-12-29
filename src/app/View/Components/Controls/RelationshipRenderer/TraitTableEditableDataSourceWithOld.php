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

    private function removeDestroyedLines(&$editableTablesTransactions, &$oldObjects)
    {
        $toBeRemoved = [];
        foreach ($editableTablesTransactions as $index => $transaction) {
            if ($transaction['msg'] == 'Destroyed' && $transaction['result'] == true) {
                $toBeRemoved[] = $index;
            }
        }
        foreach ($toBeRemoved as $index) {
            unset($editableTablesTransactions[$index]);
            unset($oldObjects[$index]);
        }
        $editableTablesTransactions = array_values($editableTablesTransactions);
        $oldObjects = array_values($oldObjects);
    }

    private function getToBeHighlightedLineIndex($editableTablesTransactions)
    {
        $toBeHighlighted = [];
        foreach ($editableTablesTransactions as $index => $transaction) {
            if ($transaction['result'] == false)  $toBeHighlighted[] = $index;
        }
        return $toBeHighlighted;
    }

    private function getOracyValues($oldValues)
    {
        $result = [];
        foreach ($oldValues as $rowIndex => $line) {
            foreach ($line as $controlName => $value) {
                if (str_contains($controlName, "()")) {
                    $result[$rowIndex] = [
                        $controlName => $value,
                    ];
                }
            }
        }
        return $result;
    }

    private function convertOldToDataSource($table01Name, $dataSource, $lineModelPath)
    {
        $old = old($table01Name);
        // dump($dataSource);
        if (is_null($old)) return $dataSource;
        $oldObjects = array_values($this->parseHTTPArrayToLines($old));
        $result = [];
        $editableTablesTransactions = session()->get('editableTablesTransactions');
        $editableTablesTransactions = $editableTablesTransactions[$table01Name] ?? [];
        // dump($editableTablesTransactions);
        // dump($oldObjects);

        $this->removeDestroyedLines($editableTablesTransactions, $oldObjects);
        $oracyValues = $this->getOracyValues($oldObjects);
        // dump($oracyValues);
        $toBeHighlightedIndex = $this->getToBeHighlightedLineIndex($editableTablesTransactions);
        // dump($toBeHighlightedIndex);

        foreach ($oldObjects as $index => $oldObject) {
            $newObject = new $lineModelPath($oldObject);
            $newObject->extraTrClass = (in_array($index, $toBeHighlightedIndex)) ? "bg-red-300" : "";
            if (isset($oracyValues[$index])) {
                foreach ($oracyValues[$index] as $key => $value) {
                    $newObject->{$key} = $value;
                }
            }
            // dump($newObject);
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
            $row->order_no = 101 + $index;
        }
        return $dataSource;
    }

    private function alertIfFieldsAreMissingFromFillable(object $instance, string $lineModelPath, array $editableColumns)
    {
        $columns = array_filter($editableColumns, fn ($column) => isset($column['editable']) && $column['editable'] == true);
        $columns = array_filter($columns, fn ($column) => !str_contains($column['dataIndex'], "()")); //<< Ignored all oracy params
        $columns = array_filter($columns, fn ($column) => $column['renderer'] !== 'attachment4'); //<< Ignored all attachments
        $columns = array_values(array_map(fn ($column) => $column['dataIndex'], $columns));
        // dump($editableColumns);
        // dump($columns);
        $columns[] = 'owner_id';
        $fillable = $instance->getFillable();
        $diff = array_diff($columns, $fillable);
        if (!empty($diff)) {
            $msg = join(", ", $diff) . " not found in fillable of $lineModelPath.";
            echo Blade::render("<x-feedback.alert type='error' message='$msg' ></x-feedback.alert>");
        }
        if (!in_array('id', $columns)) {
            $msg = "ID is missing in getManyLineParams() function.";
            echo Blade::render("<x-feedback.alert type='error' message='$msg' ></x-feedback.alert>");
        }
    }

    private function attachActionColumn($table01Name, $dataSource, $isOrderable, $readOnly)
    {
        if ($readOnly) return $dataSource;
        // dump($dataSource);
        foreach ($dataSource as $rowIndex => &$row) {
            $id = $row->order_no;
            $readOnly = $row->readOnly ?? false;
            // dump($index);
            $type = $this->tableDebug ? "text" : "hidden";
            $output = "
            <input readonly name='{$table01Name}[finger_print][$rowIndex]' value='$id' type=$type class='w-16 bg-gray-300' />
            <input readonly name='{$table01Name}[DESTROY_THIS_LINE][$rowIndex]'  type=$type class='w-16 bg-gray-300' />
            <div class='whitespace-nowrap flex justify-center '>";
            if ($isOrderable) $output .= "<x-renderer.button size='xs' value='$table01Name' onClick='moveUpEditableTable({control:this, fingerPrint: $id, nameIndex: $rowIndex})'><i class='fa fa-arrow-up'></i></x-renderer.button>
                 <x-renderer.button size='xs' value='$table01Name' onClick='moveDownEditableTable({control:this, fingerPrint: $id, nameIndex: $rowIndex})'><i class='fa fa-arrow-down'></i></x-renderer.button>";
            if (!$readOnly) $output .= "<x-renderer.button size='xs' value='$table01Name' onClick='duplicateLineEditableTable({control:this, fingerPrint: $id, nameIndex: $rowIndex})' type='secondary' ><i class='fa fa-copy'></i></x-renderer.button>
                <x-renderer.button size='xs' value='$table01Name' onClick='trashEditableTable({control:this, fingerPrint: $id, nameIndex: $rowIndex})' type='danger' ><i class='fa fa-trash'></i></x-renderer.button>
            </div>
            ";
            $row->action = Blade::render($output);
        }
        return $dataSource;
    }
}
