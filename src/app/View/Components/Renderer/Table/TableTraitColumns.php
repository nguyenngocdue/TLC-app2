<?php

namespace App\View\Components\Renderer\Table;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;

trait TableTraitColumns
{
    use TableTraitCommon;
    private function getTableWidth($columns)
    {
        $result = 0;
        foreach ($columns as $column) {
            if ($this->isInvisible($column)) continue;
            if (empty($column)) continue;
            if (isset($column['width']) && $column['width'] != '') {
                $w = $column['width'];
                $result += $w;
            }
        }
        return "width:" . $result . "px;";
    }

    private function makeColGroup($columns)
    {
        $result = [];
        foreach ($columns as $column) {
            if ($this->isInvisible($column)) continue;
            if (empty($column)) continue;
            $name = $column['dataIndex'];
            if (isset($column['width']) && $column['width'] != '') {
                $w = $column['width'];
                $result[] = "<col name='$name' style='width:{$w}px'>";
            } else {
                $result[] = "<col name='$name'>";
            }
        }
        return join("", $result);
    }

    private function makeTh($column, $isLastColumn, $elapse, $hidden)
    {
        $renderer = $column['renderer'] ?? "_no_renderer_";
        // $rendererUnit = $column['rendererUnit'] ?? "_no_unit_";
        $dataIndex = $column['dataIndex'];
        $columnName = $column['column_name'] ?? $dataIndex;
        $columnType = $column['column_type'] ?? "";
        $width = $column['width'] ?? "";
        $colspan = $column['colspan'] ?? 1;
        $title = $column['title'] ?? Str::headline($column['dataIndex']);
        $subTitle = $column['subTitle'] ?? null;
        if ($subTitle) $title .= "<hr/><i>" . $subTitle . "</i>";
        $tooltip = "Column details:\n";
        $tooltip .= "+ DataIndex: $dataIndex\n";
        $tooltip .= "+ ColumnName: $columnName\n";
        $tooltip .= "+ Renderer: $renderer\n";
        $tooltip .= "+ Width: $width\n";
        $tooltip .= "+ Colspan: $colspan\n";
        $tooltip .= "+ Took: {$elapse}ms";

        $styleStr = $this->getStyleStr($column);
        $iconJson = $columnType === 'json' ? '<br/><i title="JSON format" class="fa-duotone fa-brackets-curly"></i>' : "";
        if ($columnType === 'json') $title .= $iconJson;
        if ((env('SHOW_ELAPSE') && !app()->isProduction()) || (in_array(CurrentUser::id(), [35, 38]))) $title .= "<br/><span title='Elapse time'>({$elapse}ms)</span>";
        $rotate45Width = $this->rotate45Width;
        $rotate45Height = ($this->rotate45Width) ? $rotate45Width - 100 : false;
        $classTh = ($this->rotate45Width) ? "rotated-title-th h-[{$rotate45Height}px]" : "";
        $classDiv = ($this->rotate45Width) ? "rotated-title-div text-right w-[{$rotate45Width}px]" : "";
        $borderRight = $isLastColumn ? "" : "border1 border-r";
        $borderRight = ($this->rotate45Width) ? "" : $borderRight;
        $tinyText = $this->noCss ? "text-xs" : "";
        $colspanStr = ($colspan > 1) ? "colspan=$colspan" : "";
        $hiddenStr = $hidden ? "hidden" : "";
        $th = "";
        $th .= "<th id='$columnName' $colspanStr class='px-4 py-3 border-b border-gray-300 $borderRight $classTh $hiddenStr' $styleStr title='$tooltip'>";
        $th .= "<div class='$classDiv $tinyText text-gray-700 dark:text-gray-300'>";
        $th .= "<span>" . $title . "</span>";
        $th .= "</div>";
        $th .= "</th>";

        return $th;
    }

    private function getSkippedDueToColspan($columns)
    {
        $skippedDueToColspan = array_fill(0, count($columns), false);
        $columns = array_values($columns);
        foreach ($columns as $key => $column) {
            if ($skippedDueToColspan[$key]) continue;
            $colspan = $column['colspan'] ?? 1;
            if ($colspan > 1) {
                for ($i = 1; $i < $colspan; $i++) {
                    $skippedDueToColspan[$i + $key] = true;
                }
            }
        }
        return $skippedDueToColspan;
    }

    private function getColumnRendered($columns, $timeElapse)
    {
        $columnsRendered = [];
        $skippedDueToColspan = $this->getSkippedDueToColspan($columns);
        $columns = array_values($columns);
        foreach ($columns as $key => $column) {
            if (empty($column)) continue;
            // if ($skippedDueToColspan[$key]) continue;
            $hidden = $this->isInvisible($column);
            $hidden = $hidden || $skippedDueToColspan[$key];
            // if (!$this->isInvisible($column)) {
            $dataIndex = $column['dataIndex'];
            $elapse = $timeElapse[$dataIndex] ?? 0;
            $columnsRendered[] = $this->makeTh($column, $key == sizeof($columns) - 1, $elapse, $hidden);
            // }
        }
        $columnsRendered = join("", $columnsRendered);
        return $columnsRendered;
    }

    private function makeNoColumn($columns)
    {
        $columnNo = ["title" => "No.", "renderer" => "no.", "dataIndex" => "auto.no.", 'align' => 'center', "width" => '50'];
        if ($this->showNo) array_unshift($columns, $columnNo);
        if ($this->showNoR) array_push($columns, $columnNo);
        return $columns;
    }

    private function makeTable2ndThead($columns, $dataHeader)
    {
        if (is_null($dataHeader)) return "";
        if (empty($dataHeader)) return "";
        // dump($dataHeader);
        $th = [];
        $columns = array_values($columns);
        foreach ($columns as $key => $column) {
            if ($this->isInvisible($column)) continue;
            $styleStr = $this->getStyleStr($column);
            $dataIndex = $column['dataIndex'];
            $borderR = $key < (sizeof($columns) - 1) ? 'border-r' : "";
            if (isset($dataHeader[$dataIndex])) {
                if (is_object($dataHeader[$dataIndex])) {
                    $cell_class = $dataHeader[$dataIndex]->cell_class;
                    $cell_title = $dataHeader[$dataIndex]->cell_title;
                    $th[] = "<th $styleStr class='py-1 $borderR border-b $cell_class' title='$cell_title'>" . $dataHeader[$dataIndex]->value . "</th>";
                } else {
                    $th[] = "<th $styleStr class='py-1 $borderR border-b'>" . $dataHeader[$dataIndex] . "</th>";
                }
            } else {
                $th[] = "<th $styleStr class='py-1 $borderR border-b' dataIndex='" . $dataIndex . "'></th>";
            }
        }
        $result = join($th);
        return $result;
    }

    private function hideColumns($columns)
    {
        $columns = array_filter($columns, fn ($column) => !(isset($column['hidden']) &&  $column['hidden'] == true));
        // dump($columns);
        return $columns;
    }
}
