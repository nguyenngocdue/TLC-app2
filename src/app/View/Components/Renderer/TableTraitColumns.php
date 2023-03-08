<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Str;

trait TableTraitColumns
{
    use TableTraitCommon;
    private function makeColGroup($columns)
    {
        $result = [];
        foreach ($columns as $column) {
            if ($this->isInvisible($column)) continue;
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

    private function makeTh($column, $isLastColumn)
    {
        $renderer = $column['renderer'] ?? "_no_renderer_";
        // $rendererUnit = $column['rendererUnit'] ?? "_no_unit_";
        $dataIndex = $column['dataIndex'];
        $columnName = $column['column_name'] ?? $dataIndex;
        $columnType = $column['column_type'] ?? "";
        $width = $column['width'] ?? "";
        $title = $column['title'] ?? Str::headline($column['dataIndex']);
        $tooltip = "Column details:\n";
        $tooltip .= "+ DataIndex: $dataIndex\n";
        $tooltip .= "+ ColumnName: $columnName\n";
        $tooltip .= "+ Renderer: $renderer\n";
        // $tooltip .= "+ RendererUnit: $rendererUnit\n";
        $tooltip .= "+ Width: $width";
        $styleStr = $this->getStyleStr($column);
        $iconJson = $columnType === 'json' ? '<br/><i title="JSON format" class="fa-duotone fa-brackets-curly"></i>' : "";
        if ($columnType === 'json') $title .= $iconJson;
        $rotate45Width = $this->rotate45Width;
        $rotate45Height = ($this->rotate45Width) ? $rotate45Width - 100 : false;
        $classTh = ($this->rotate45Width) ? "rotated-title-th h-[{$rotate45Height}px]" : "";
        $classDiv = ($this->rotate45Width) ? "rotated-title-div text-right w-[{$rotate45Width}px]" : "";
        $borderRight = $isLastColumn ? "" : "border border-r-2 border-t-0";
        $borderRight = ($this->rotate45Width) ? "" : $borderRight;
        $tinyText = $this->noCss ? "text-xs" : "";
        $th = "";
        $th .= "<th class='px-4 py-3 $borderRight $classTh' $styleStr title='$tooltip'>";
        $th .= "<div class='$classDiv $tinyText text-gray-700 dark:text-gray-300'>";
        $th .= "<span>" . $title . "</span>";
        $th .= "</div>";
        $th .= "</th>";

        return $th;
    }

    private function getColumnRendered($columns)
    {
        $columnsRendered = [];
        foreach ($columns as $key => $column) {
            if (!$this->isInvisible($column)) $columnsRendered[] = $this->makeTh($column, $key == sizeof($columns) - 1);
        }
        $columnsRendered = join("", $columnsRendered);
        return $columnsRendered;
    }

    private function makeNoColumn($columns)
    {
        $columnNo = ["title" => "No.", "renderer" => "no.", "dataIndex" => "auto.no.", 'align' => 'center', "width" => '10'];
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
        foreach ($columns as $column) {
            if ($this->isInvisible($column)) continue;
            $styleStr = $this->getStyleStr($column);
            $dataIndex = $column['dataIndex'];
            if (isset($dataHeader[$dataIndex])) $th[] = "<th class='py-1' $styleStr>" . $dataHeader[$dataIndex] . "</th>";
            else $th[] = "<th $styleStr dataIndex='" . $dataIndex . "'></th>";
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
