<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

trait TableTraitRows
{
    use TableTraitApplyRender;

    private function smartGetItems($dataSource)
    {
        if (is_null($dataSource)) return null;
        if (is_array($dataSource)) return $dataSource;
        $reflect = new ReflectionClass($dataSource);
        // dd($reflect->getShortName());
        switch ($reflect->getShortName()) {
            case 'Collection':
                return $dataSource->all();
            case 'LengthAwarePaginator':
                return $dataSource->items();
            default:
                break;
        }
        return $dataSource->items();
    }

    private function makeTd($columns, $dataLine, $no, $dataLineIndex, $tableDebug)
    {
        $tds = [];
        $columnCount = sizeof($columns);
        // Log::info($columns);
        foreach (array_values($columns) as $index => $column) {
            $renderer = $column['renderer'] ?? false;
            $columnName = $column['column_name'] ?? $column['dataIndex'];
            $name = isset($column['dataIndex']) ? "{$this->tableName}[$columnName][$dataLineIndex]" : "";
            $rawData = null;
            switch ($renderer) {
                case  'no.':
                    // dd($start, $no);
                    $rendered = "<p class='p-2'>" . $no . "<p>";
                    break;
                default:
                    $dataIndex = $column['dataIndex'];
                    if (str_contains($dataIndex, "()")) {
                        $fn = substr($dataIndex, 0, strlen($dataIndex) - strlen("()"));
                        $rawData = $dataLine->$fn() ?? ""; //this is to execute the getCheckedByField function
                    } else {
                        $rawData = (is_object($dataLine))  ? ($dataLine->$dataIndex ?? "") : ($dataLine[$dataIndex] ?? "");
                    }
                    $rawData = is_array($rawData) ? count($rawData) . " items" : $rawData;
                    $valueOfRawData = is_object($rawData) ? $rawData->value : $rawData;
                    $rendered = $renderer
                        // ? "A" 
                        // : "B";
                        ? $this->applyRender($name, $renderer, $rawData, $column, $dataLine, $dataLineIndex)
                        : "<p class='p-2'>" . $valueOfRawData . "</p>";
                    break;
            }
            $align = ($column['align'] ?? null) ? "text-" . $column['align'] : "";
            $borderRight = ($index < $columnCount - 1) ? "border-r" : "";
            $hidden = $this->isInvisible($column) ? "hidden" : "";
            $styleStr = $this->getStyleStr($column);
            $rendered = ($tableDebug && ($renderer != 'no.') ? $name : "") . $rendered;

            // $cellColor = (is_object($dataLine))  ? ($dataLine->cell_color ?? "") : ($dataLine['cell_color'] ?? "");
            $cellColor = '';
            if (is_object($rawData)) {
                if (isset($rawData->cell_color)) {
                    $cellColor = $rawData->cell_color;
                }
            }
            $tds[] = "<td class='$cellColor $hidden  dark:border-gray-600 $borderRight $align' $styleStr>" . $rendered . "</td>";
        }
        return $tds;
    }

    private function makeTrTd($columns, $dataSource, $tableDebug, $table01Name)
    {
        $trs = [];
        $colspan = sizeof($columns);

        $items = $this->smartGetItems($dataSource);

        if (is_null($dataSource)) return "<tr><td colspan=$colspan>" . Blade::render("<x-feedback.alert type='error' message='DataSource attribute is missing.' />") . "</td></tr>";
        if (empty($dataSource) || (is_object($dataSource) && empty($items))) return "<tr id='{$table01Name}_emptiness'><td colspan=$colspan>" . Blade::render("<x-renderer.emptiness/>") . "</td></tr>";

        // $columnCount = count($columns);
        $start = (is_object($dataSource) && method_exists($dataSource, 'items')) ?  $dataSource->perPage() * ($dataSource->currentPage() - 1) : 0;
        if ($this->groupBy && !$this->groupKeepOrder) {
            if (is_object($dataSource)) $dataSource = $items;
            usort($dataSource, fn ($a, $b) => strcasecmp($a[$this->groupBy] ?? 'zzz', $b[$this->groupBy] ?? 'zzz'));
        }

        $lastIndex = "anything";
        foreach ($dataSource as $no => $dataLine) {
            $tds = $this->makeTd($columns, $dataLine, $start + $no + 1, $no, $tableDebug);

            if ($this->groupBy) {
                $index = isset($dataLine[$this->groupBy][0]) ? strtoupper(substr($dataLine[$this->groupBy], 0, $this->groupByLength)) : "(EMPTY)";
                if ($index !== $lastIndex) {
                    $lastIndex = $index;
                    $trs[] = "<tr class='bg-gray-100 dark:bg-gray-800'><td class='p-2 text-lg font-bold text-gray-600 dark:text-gray-300' colspan=$colspan>{$index}</td></tr>";
                }
            }
            if (is_array($dataLine)) {
                $bgClass = ($dataLine['row_color'] ?? false) ? "bg-" . $dataLine['row_color'] . "-400" : "";
            } else {
                $bgClass = '';
            }
            $extraTrClass = $dataLine->extraTrClass ?? "";
            $tr = "<tr class='dark:hover:bg-gray-600 hover:bg-gray-200 $bgClass text-gray-700 dark:text-gray-300 $extraTrClass'>";
            $tr .=  join("", $tds);
            $tr .= "</tr>";
            $trs[] = $tr;

            if (is_array($dataLine)) {
                if (isset($dataLine['rowDescription'])) {
                    $colspan_minus_1 = $colspan - 1;
                    $td = "<td class='p-2 text-xs dark:text-gray-300 text-gray-600' colspan=$colspan_minus_1>{$dataLine['rowDescription']}</td>";
                    $trs[] = "<tr component='rowDescription' class='dark:bg-gray-600  bg-gray-100 '><td></td>$td</tr>";
                }
            }
        }
        $tr_td = join("", $trs);
        return $tr_td;
    }
}
