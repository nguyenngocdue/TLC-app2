<?php


namespace App\View\Components\Controls\RelationshipRenderer;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Support\Str;

trait TraitTableColumnEditable
{
    private function makeEditableColumns($columns, $sp, $tableName, $table01Name)
    {
        $result = [['dataIndex' => 'action', 'width' => 5,]];
        foreach ($columns as $column) {
            $newColumn = $column;
            $prop = $sp['props']["_" . $column['dataIndex']];
            // dump($prop);
            $newColumn['title'] = $column['title'] ?? $prop['label']; //. " <br/>" . $prop['control'];
            $newColumn['width'] = $prop['width'];
            $validation = $prop['default-values']['validation'] ?? "";
            $isRequired = in_array("required", explode("|", $validation));
            $newColumn['title'] .= $isRequired ? "</br><i class='text-red-400' title='required'>*</i>" : "";
            $newColumn['column_type'] = $prop['column_type'];
            // dump($newColumn);
            switch ($prop['control']) {
                case 'id':
                    $newColumn['renderer'] = 'read-only-text';
                    $newColumn['editable'] = true;
                    $newColumn['align'] = 'center';
                    break;
                case 'status':
                    $newColumn['cbbDataSourceObject'] = LibStatuses::getFor($tableName);
                    $newColumn['cbbDataSource'] = array_keys(LibStatuses::getFor($tableName));
                    $newColumn['renderer'] = 'dropdown';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = "block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 text-left placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm";
                    break;
                case 'dropdown':
                case 'dropdown_multi':
                case 'checkbox':
                case 'radio':
                    // $newColumn['renderer'] = 'text';
                    $newColumn['renderer'] = 'dropdown4';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = "block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 text-left placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm";

                    $newColumn['type'] = $this->type;
                    $newColumn['lineType'] = Str::singular($tableName);
                    $newColumn['table'] = $prop['relationships']['table'];
                    $newColumn['table01Name'] = $table01Name;
                    // dump($newColumn);
                    break;
                case 'textarea':
                    $newColumn['renderer'] = 'textarea';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = "bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input";
                    break;
                case 'number':
                    $newColumn['renderer'] = 'number';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = "text-right block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm";
                    break;
                default:
                    $newColumn['renderer'] = "text";
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = " block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm";
                    break;
            }
            if (in_array($prop['control'], ['dropdown_multi', 'checkbox'])) {
                $newColumn['multiple'] = true;
            }
            if ($newColumn['dataIndex'] === 'order_no') {
                $newColumn['onChange'] = "rerenderTableBaseOnNewOrder(`" . $table01Name . "`)";
            }
            // dump($newColumn);
            $result[] = $newColumn;
        }
        // dump($result);
        return $result;
    }
}
