<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\JsonControls;
use App\View\Components\Renderer\Table\TableTraitFooter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitEntityEditableTableFooter
{
    use TraitEntityListenDataSource;
    use TableTraitFooter;

    private function split($string)
    {
        $pattern = '/\[([^\]]+)\]/'; // Matches anything inside square brackets

        if (preg_match_all($pattern, $string, $matches)) {
            $results = $matches[1];
            return $results;
        } else {
            return null;
        }
    }

    private function getListenFooter($tableName, $tableFnNames, $table01Name)
    {
        //In case of item-renderer-checklist has no table
        if (!isset($tableFnNames[$table01Name])) return [];

        $columnName = $tableFnNames[$table01Name];
        // Log::info($tableName . " " . $table01Name . " " . $columnName . " " . $this->type);
        $listener2 = $this->getListeners2($this->type);
        $allListenFooters = array_filter($listener2, fn ($line) => $line['listen_action'] == 'aggregate_from_table');
        $listenFooters = [];
        foreach ($allListenFooters as $listener) {
            foreach ($listener['triggers'] as $trigger) {
                $functionName = Str::before($trigger, '[');
                // if (str_starts_with($trigger, $columnName)) { //This cause issue when getRuns and getRunsRework
                if ($columnName == $functionName) {
                    [$footer, $tableColumn, $aggName] = $this->split($trigger);
                    if ($footer === 'footer') {
                        $listenFooters[$columnName][] = [
                            'tableColumn' => $tableColumn,
                            'aggName' => $aggName,
                            'targetField' => $listener['column_name']
                        ];
                    }
                }
            }
        }
        return $listenFooters;
    }

    //This called when delete a line in table, all agg fields need to be updated
    private function recalculateAggregatedFields($tableName, $table01Name, $tableFnNames, $parentId)
    {
        $sp = SuperProps::getFor($tableName);
        $listenFooters = $this->getListenFooter($tableName, $tableFnNames, $table01Name);
        $modelPath = Str::modelPathFrom($this->type);
        // Log::info($tableName . " " . $modelPath . " " . $parentId);

        $result = [];
        foreach ($listenFooters as $fn => $listenerList) {
            $item = $modelPath::find($parentId);
            $dataSource = $item->{$fn};
            foreach ($listenerList as $listener) {
                $fieldName = $listener['tableColumn'];
                $control = ($sp['props']["_" . $fieldName]['control']);

                $allFooterLines = $this->makeOneFooterRaw($fieldName, $control, $fn, $dataSource, false);
                $aggName = $listener['aggName'];
                $newValue = $allFooterLines[$aggName];

                $targetField = $listener['targetField'];
                // $item->total = $newValue; //<< Update here will be overwritten by the_form's flow
                // Log::info("Update item $tableName #$parentId ($modelPath) $targetField with value $newValue");
                if (in_array($control, JsonControls::getDateTimeControls())) {
                    $newValue = DateTimeConcern::convertForSaving($control, $newValue);
                }
                $result[$targetField] = $newValue;
            }
        }
        // Log::info($result);
        return $result;
    }
}
