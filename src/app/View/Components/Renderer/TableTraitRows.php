<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use ReflectionClass;

trait TableTraitRows
{
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

    private function makeTd($columns, $dataLine, $columnCount, $no, $dataLineIndex)
    {
        $tds = [];
        // Log::info($columns);
        foreach (array_values($columns) as $index => $column) {
            $renderer = $column['renderer'] ?? false;
            switch ($renderer) {
                case  'no.':
                    // dd($start, $no);
                    $rendered = $no;
                    break;
                default:
                    $dataIndex = $column['dataIndex'];
                    if (str_contains($dataIndex, "()")) {
                        $fn = substr($dataIndex, 0, strlen($dataIndex) - strlen("()"));
                        $rawData = $dataLine->$fn() ?? ""; //this is to execute the getCheckedByField function
                    } else {
                        $rawData = $dataLine[$dataIndex] ?? "";
                    }
                    $rawData = is_array($rawData) ? count($rawData) . " items" : $rawData;
                    $rendered = $renderer
                        // ? "A" 
                        // : "B";
                        ? $this->applyRender($renderer, $rawData, $column, $dataLine, $dataLineIndex)
                        : $rawData;
                    break;
            }
            $align = ($column['align'] ?? null) ? "text-" . $column['align'] : "";
            $borderRight = ($index < $columnCount - 1) ? "border-r" : "";
            $tds[] = "<td class='px-1 py-1 $borderRight $align'>" . $rendered . "</td>";
        }
        return $tds;
    }

    private function makeTrTd($columns, $dataSource)
    {
        $trs = [];
        $colspan = sizeof($columns);

        $items = $this->smartGetItems($dataSource);

        if (is_null($dataSource)) return "<tr><td colspan=$colspan>" . Blade::render("<x-feedback.alert type='error' message='DataSource attribute is missing.' />") . "</td></tr>";
        if (empty($dataSource) || (is_object($dataSource) && empty($items))) return "<tr><td colspan=$colspan>" . Blade::render("<x-renderer.emptiness/>") . "</td></tr>";

        $columnCount = count($columns);
        $start = (is_object($dataSource) && method_exists($dataSource, 'items')) ?  $dataSource->perPage() * ($dataSource->currentPage() - 1) : 0;
        if ($this->groupBy) {
            if (is_object($dataSource)) $dataSource = $items;
            usort($dataSource, fn ($a, $b) => strcasecmp($a[$this->groupBy], $b[$this->groupBy]));
        }

        $lastIndex = "anything";
        foreach ($dataSource as $no => $dataLine) {
            $tds = $this->makeTd($columns, $dataLine, $columnCount, $start + $no + 1, $no);

            if ($this->groupBy) {
                if (isset($dataLine[$this->groupBy][0])) { //<< this to make sure an item with empty name doesn't crash the app
                    $index = strtoupper(substr($dataLine[$this->groupBy], 0, $this->groupByLength));
                    if ($index !== $lastIndex) {
                        $lastIndex = $index;
                        $trs[] = "<tr class='bg-gray-100 '><td class='p-2 text-lg font-bold text-gray-600' colspan=$colspan>{$index}</td></tr>";
                    }
                }
            }

            $bgClass = ($dataLine['row_color'] ?? false) ? "bg-" . $dataLine['row_color'] . "-400" : "";
            $trs[] = "<tr class='hover:bg-gray-100 $bgClass text-gray-700 dark:text-gray-400'>" . join("", $tds) . "</tr>";

            if (isset($dataLine['rowDescription'])) {
                $colspan_minus_1 = $colspan - 1;
                $trs[] = "<tr class='bg-gray-100 '><td></td><td class='p-2 text-xs text-gray-600' colspan=$colspan_minus_1>{$dataLine['rowDescription']}</td></tr>";
            }
        }
        $tr_td = join("", $trs);

        return $tr_td;
    }
}
