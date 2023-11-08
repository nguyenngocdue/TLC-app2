<?php

namespace App\View\Components\Renderer\Table;

use App\Utils\System\Timer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ReflectionClass;

trait TableTraitRows
{
    use TableTraitApplyRender;
    use TableTraitCommon;
    public $timeElapse = [];

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

    private function parseCellObject($rawData)
    {
        $cellClassList = '';
        $cellTitle = '';
        $cellHref = '';
        $cellOnClick = '';
        $value = '';
        if (is_object($rawData)) {
            if (isset($rawData->cell_class)) $cellClassList = $rawData->cell_class;
            if (isset($rawData->cell_title)) $cellTitle = $rawData->cell_title;
            if (isset($rawData->cell_href)) $cellHref = $rawData->cell_href;
            if (isset($rawData->cell_onclick)) $cellOnClick = $rawData->cell_onclick;
            if (isset($rawData->value)) $value = $rawData->value;
        }
        return [$cellClassList, $cellTitle, $cellHref, $cellOnClick, $value];
    }

    private function makeTd($columns, $dataLineObj, $no, $dataLineIndex, $batchLength, $tableDebug)
    {
        $tds = [];
        $columnCount = sizeof($columns);
        // Log::info($columns);
        $columns = array_values($columns);
        foreach ($columns as $index => $column) {
            // dump($column);
            $fixedLeft = $this->getFixedLeftOrRight($column, $index, "left", "td");
            $fixedRight = $this->getFixedLeftOrRight($column, $index, "right", "td");

            $renderer = $column['renderer'] ?? false;
            $columnName = $column['column_name'] ?? $column['dataIndex'];
            $name = isset($column['dataIndex']) ? "{$this->tableName}[$columnName][$dataLineIndex]" : "";

            $rawData = null;
            switch ($renderer) {
                case  'no.':
                    // dd($start, $no);
                    $rendered = "<p class='p-2'>" . $no . "</p>";
                    break;
                default:
                    $dataIndex = $column['dataIndex'];
                    if (str_contains($dataIndex, "()")) {
                        $fn = substr($dataIndex, 0, strlen($dataIndex) - strlen("()"));
                        //<< this is to execute the getCheckedByField function
                        $rawData = method_exists($dataLineObj, $fn) ? $dataLineObj->$fn() : "?";
                    } else {
                        $rawData = $dataLineObj->$dataIndex ?? "";
                    }
                    // $rawData = is_array($rawData) ? count($rawData) . " items" : $rawData;
                    $isArrayOfValueObject = false;
                    if (is_array($rawData)) {
                        $isArrayOfValueObject = true;
                        foreach ($rawData as $item) {
                            if (!isset($item->value)) {
                                $isArrayOfValueObject = false;
                                break;
                            }
                        }
                        if ($isArrayOfValueObject) {
                            $output = "";
                            foreach ($rawData as $item) {
                                $obj = $this->parseCellObject($item);
                                [$cellClassList, $cellTitle, $cellHref, $cellOnClick, $value] = $obj;
                                $div = "<span class='$cellClassList mx-0.5 px-2 py-1 rounded' title='$cellTitle'>" . $value . "</span>";
                                if ($cellHref) $div = "<a href='$cellHref' onclick='$cellOnClick'>" . $div . "</a>";
                                $output .= $div;
                            }
                            $rawData = $output;
                        } else {
                            $rawData = count($rawData) . " items";
                        }
                    }

                    if (is_object($rawData)) {
                        if (isset($rawData->value)) {
                            $valueOfRawData = $rawData->value;
                        } else {
                            $valueOfRawData = ""; //<< Render empty string when NULL or UNSET
                        }
                    } else {
                        $valueOfRawData = $rawData;
                    }

                    $cellDivClass = '';
                    if (is_object($rawData)) {
                        if (isset($rawData->cell_div_class)) $cellDivClass = $rawData->cell_div_class;
                    }
                    if ($isArrayOfValueObject) {
                        $cellDivClass = "flex justify-evenly";
                    }
                    $rendered = $renderer
                        // ? "A" 
                        // : "B";
                        ? $this->applyRender($name, $renderer, $rawData, $column, $dataLineObj, $dataLineIndex, $batchLength)
                        : ($dataIndex === 'action'
                            ?
                            $valueOfRawData
                            :
                            "<div class='p-2 $cellDivClass' valueOfRawData>" . (is_object($valueOfRawData) ? "[object]" : $valueOfRawData) . "</div>"
                        );
                    break;
            }
            $align = ($column['align'] ?? null) ? "text-" . $column['align'] : "";
            $borderRight = ($index < $columnCount - 1) ? "border-r" : "";
            $hidden = $this->isInvisible($column) ? "hidden" : "";
            $styleStr = $this->getStyleStr(['width' => ($column['width'] ?? 100) . "px", 'a' => 3]);
            $rendered = ($tableDebug && ($renderer != 'no.') ? $name : "") . $rendered;

            [$cellClassList, $cellTitle, $cellHref, $cellOnClick] = $this->parseCellObject($rawData);
            $breakWords = $this->noCss ? "break-all123" : "";
            $tinyText = $this->noCss ? "text-xs" : "";
            $borderGray = $this->noCss ? "border-gray-200" : "";
            $bgWhite = ($renderer == 'no.') ? "bg-white" : "";
            $nowrap = ($column['nowrap'] ?? false) ? "whitespace-nowrap" : "";
            $td = "<td class='$fixedLeft $fixedRight $bgWhite dark:border-gray-600 border-b $tinyText $breakWords $cellClassList $hidden $borderRight $borderGray $align $nowrap'";
            $td .= $styleStr;
            $td .= $cellTitle ? "title='$cellTitle'" : "";
            $td .= ">";
            if ($cellHref) $td .= "<a href='$cellHref' onclick='$cellOnClick'>";
            $td .= $rendered;
            if ($cellHref) $td .= "</a>";
            $td .= "</td>";

            $tds[] = Str::removeDuplicateSpaces($td);

            if (!isset($this->timeElapse[$columnName])) $this->timeElapse[$columnName] = 0;
            $this->timeElapse[$columnName] += Timer::getTimeElapseFromLastAccess();
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
            $groupBy = $this->groupBy;
            uasort($dataSource, function ($a, $b) use ($groupBy) {
                $aValue = is_object($a) ? ($a->{$groupBy} ?? 'zzz') : ($a[$this->groupBy] ?? 'zzz');
                $bValue = is_object($a) ? ($a->{$groupBy} ?? 'zzz') : ($b[$this->groupBy] ?? 'zzz');
                return strcasecmp($aValue, $bValue);
            });
        }

        $lastIndex = -1;
        $lineNo = 0;
        foreach ($dataSource as $dataLineIndex => $dataLine) {
            $dataLineObj = is_object($dataLine) ? $dataLine : (object)$dataLine;
            $tds = $this->makeTd($columns, $dataLineObj, $start + $lineNo++ + 1, $dataLineIndex, sizeof($dataSource), $tableDebug);

            if ($this->groupBy) {
                $groupBy = $this->groupBy;
                $index = isset($dataLineObj->{$groupBy}[0]) ? strtoupper(substr($dataLineObj->{$groupBy}, 0, $this->groupByLength)) : "(EMPTY)";
                if ($index !== $lastIndex) {
                    $lastIndex = $index;
                    //<<This fixedLeft still doesn't work
                    $fixedLeft = "table-th-fixed-left table-th-fixed-left-0";
                    $tr = "<tr class='bg-gray-100 dark:bg-gray-800'>";
                    $tr .= "<td class='$fixedLeft bg-white1 p-2 border-b text-lg font-bold text-gray-600 dark:text-gray-300' colspan=$colspan>{$index}</td>";
                    $tr .= "</tr>";
                    $trs[] = $tr;
                }
            }
            $bgClass = ($dataLineObj->row_color ?? false) ? "bg-" . $dataLineObj->row_color . "-400" : "";
            $extraTrClass = $dataLineObj->extraTrClass ?? "";
            $tr = "<tr class='dark:hover:bg-gray-900 hover:bg-gray-100 $bgClass text-gray-700 dark:text-gray-300 $extraTrClass'>";
            $tr .=  join("", $tds);
            $tr .= "</tr>";
            $trs[] = $tr;

            if (isset($dataLineObj->rowDescription)) {
                $colspan_minus_1 = $colspan - 1;

                $td = "<td class='p-2 border-b text-xs dark:text-gray-300 text-gray-600' colspan=$colspan_minus_1>{$dataLineObj->rowDescription}</td>";
                $trs[] = "<tr component='rowDescription' class='dark:bg-gray-600  bg-gray-100 '><td class='border-b'></td>$td</tr>";
            }
        }
        $tr_td = join("", $trs);
        return $tr_td;
    }
}
