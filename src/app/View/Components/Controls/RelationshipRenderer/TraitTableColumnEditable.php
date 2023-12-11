<?php


namespace App\View\Components\Controls\RelationshipRenderer;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\ClassList;
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
                dump($msg);
                $result[] = $newColumn;
                continue;
            }
            $prop = $sp['props']["_" . $column['dataIndex']];
            $newColumn['title'] = $column['title'] ?? $prop['label']; //. " <br/>" . $prop['control'];
            $labelExtra = $prop['default-values']['label_extra'] ?? "";

            $newColumn['width'] = $prop['width'];
            $validation = $prop['default-values']['validation'] ?? "";
            $isRequired = in_array("required", explode("|", $validation));
            $newColumn['column_type'] = $prop['column_type'];

            $newColumn['properties']['lineType'] = Str::singular($tableName);
            $newColumn['properties']['lineTypeTable'] = Str::plural($tableName);
            $dummyRoute = route("$tableName.edit", "_id_");
            $newColumn['properties']['lineTypeRoute'] = substr($dummyRoute, 0, strpos($dummyRoute, "_id_"));
            $newColumn['properties']['table01Name'] = $table01Name;

            $isReadOnly = ($prop['read_only'] ?? false) === 'true'; //<<CONFIG_MIGRATE
            $newColumn['properties']['readOnly'] = $isReadOnly;

            $newColumn['title'] .= "</br><i class=''>" . $labelExtra . "</i>";
            $newColumn['title'] .= $isRequired ? "</br><i class='text-red-400' title='required'>*</i>" : "";

            $isSaveOnChange = ($prop['save_on_change'] ?? false) === 'true'; //<<CONFIG_MIGRATE
            $newColumn['properties']['saveOnChange'] = $isSaveOnChange;
            $newColumn['title'] .= $isSaveOnChange ? "</br><i class='fa-duotone fa-floppy-disk' title='Save On Change'></i>" : "";

            // dump($newColumn);
            $classListText = ClassList::TEXT;
            if ($isReadOnly) $classListText = "readonly $classListText";
            switch ($prop['control']) {
                case 'id':
                    $newColumn['renderer'] = 'read-only-text4';
                    $newColumn['editable'] = true;
                    $newColumn['align'] = 'center';
                    break;
                case 'status':
                    $newColumn['cbbDataSourceObject'] = LibStatuses::getFor($tableName);
                    $newColumn['cbbDataSource'] = array_keys(LibStatuses::getFor($tableName));
                    // $newColumn['renderer'] = 'text';
                    $newColumn['renderer'] = 'dropdown';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = $classListText;
                    $newColumn['align'] = 'center';
                    break;
                case 'dropdown':
                case 'dropdown_multi':
                case 'checkbox':
                case 'radio':
                    // $newColumn['renderer'] = 'text';
                    $newColumn['renderer'] = 'dropdown4';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = $classListText;
                    $newColumn['type'] = $this->type;
                    if (isset($prop['relationships']['table'])) {
                        $newColumn['properties']['tableName'] = $prop['relationships']['table'];
                    } else {
                        dd("Cant find table of [" . $prop['name'] . "] when making table column.");
                    }
                    break;
                case 'textarea':
                    $newColumn['renderer'] = 'textarea4';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = $classListText;
                    break;
                case 'number':
                    $newColumn['renderer'] = 'number4';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = 'text-right ' . $classListText;
                    $newColumn['properties']['numericScale'] = $prop['numeric_scale'];
                    break;
                case 'toggle':
                    $newColumn['renderer'] = 'toggle4';
                    $newColumn['editable'] = true;
                    $newColumn['align'] = 'center';
                    $newColumn['classList'] = $classListText;
                    break;
                case 'picker_time':
                case 'picker_date':
                case 'picker_datetime':
                case 'picker_week':
                case 'picker_month':
                case 'picker_quarter':
                case 'picker_year':
                    $newColumn['renderer'] = 'picker-all4';
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = $classListText;
                    $newColumn['properties']['control'] = $prop['control'];
                    $newColumn['properties']['placeholder'] = Str::getPickerPlaceholder($prop['control']);
                    break;
                case 'attachment':
                    $newColumn['renderer'] = "attachment4";
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = $classListText;
                    break;
                default:
                    $newColumn['renderer'] = "text4";
                    $newColumn['editable'] = true;
                    $newColumn['classList'] = $classListText;
                    break;
            }
            if (in_array($prop['control'], ['dropdown_multi', 'checkbox'])) {
                $newColumn['multiple'] = true;
            }
            if ($newColumn['dataIndex'] === 'order_no') {
                $newColumn['onChange'] = "reRenderTableBaseOnNewOrder(`" . $table01Name . "`);";
            }
            // dump($newColumn);
            $result[] = $newColumn;
        }
        // dump($result);
        return $result;
    }
}
