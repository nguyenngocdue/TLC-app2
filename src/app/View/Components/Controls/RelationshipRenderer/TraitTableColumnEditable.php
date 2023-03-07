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
            if (!isset($sp['props']["_" . $column['dataIndex']])) {
                $msg = "Key _" . $column['dataIndex'] . " not found in props.json of $tableName ($table01Name)";
                dd($msg);
            }
            $prop = $sp['props']["_" . $column['dataIndex']];
            // dump($prop);
            $newColumn['title'] = $column['title'] ?? $prop['label']; //. " <br/>" . $prop['control'];
            $newColumn['width'] = $prop['width'];
            $validation = $prop['default-values']['validation'] ?? "";
            $isRequired = in_array("required", explode("|", $validation));
            $newColumn['column_type'] = $prop['column_type'];

            $newColumn['properties']['lineType'] = Str::singular($tableName);
            $newColumn['properties']['table01Name'] = $table01Name;

            $isReadOnly = ($prop['read_only'] ?? false) === 'true'; //<<CONFIG_MIGRATE
            $newColumn['properties']['readOnly'] = $isReadOnly;

            $newColumn['title'] .= $isRequired ? "</br><i class='text-red-400' title='required'>*</i>" : "";

            $isSaveOnChange = ($prop['save_on_change'] ?? false) === 'true'; //<<CONFIG_MIGRATE
            $newColumn['properties']['saveOnChange'] = $isSaveOnChange;
            $newColumn['title'] .= $isSaveOnChange ? "</br><i class='fa-duotone fa-floppy-disk' title='Save On Change'></i>" : "";

            // dump($newColumn);
            $classNameText = "block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm";
            if ($isReadOnly) $classNameText = "readonly $classNameText";
            switch ($prop['control']) {
                case 'id':
                    $newColumn['renderer'] = 'read-only-text';
                    $newColumn['editable'] = true;
                    $newColumn['align'] = 'center';
                    break;
                case 'status':
                    $newColumn['cbbDataSourceObject'] = LibStatuses::getFor($tableName);
                    $newColumn['cbbDataSource'] = array_keys(LibStatuses::getFor($tableName));
                    // $newColumn['renderer'] = 'text';
                    $newColumn['renderer'] = 'dropdown';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = $classNameText;
                    break;
                case 'dropdown':
                case 'dropdown_multi':
                case 'checkbox':
                case 'radio':
                    // $newColumn['renderer'] = 'text';
                    $newColumn['renderer'] = 'dropdown4';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = $classNameText;
                    $newColumn['type'] = $this->type;
                    $newColumn['properties']['tableName'] = $prop['relationships']['table'];

                    // dump($newColumn);
                    break;
                case 'textarea':
                    $newColumn['renderer'] = 'textarea';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = $classNameText;
                    break;
                case 'number':
                    $newColumn['renderer'] = 'number';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = 'text-right ' . $classNameText;
                    break;
                case 'toggle':
                    $newColumn['renderer'] = 'toggle';
                    $newColumn['editable'] = true;
                    $newColumn['align'] = 'center';
                    $newColumn['classList'] = $classNameText;
                    break;
                case 'picker_time':
                    $newColumn['renderer'] = 'picker-time4';
                    $newColumn['editable'] = true;
                    $newColumn['properties']['icon'] = 'fa-duotone fa-clock';
                    $newColumn['properties']['placeholder'] = 'HH:MM';
                    $newColumn['classList'] = $classNameText;
                    break;
                case 'picker_date':
                    $newColumn['renderer'] = 'picker-date4';
                    $newColumn['editable'] = true;
                    $newColumn['properties']['placeholder'] = 'DD/MM/YYYY';
                    $newColumn['classList'] = $classNameText;
                    break;
                case 'picker_datetime':
                    $newColumn['renderer'] = 'picker-datetime4';
                    $newColumn['editable'] = true;
                    $newColumn['properties']['placeholder'] = 'DD/MM/YYYY HH:MM';
                    $newColumn['classList'] = $classNameText;
                    break;
                case 'attachment':
                    $newColumn['renderer'] = "text";
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = 'bg-red-600 ' . $classNameText;
                    break;
                default:
                    $newColumn['renderer'] = "text";
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = $classNameText;
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
