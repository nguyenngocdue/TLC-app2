<?php

namespace App\View\Components\Renderer\Table;

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

    private function makeTh($column, $index, $elapse, $hidden, $table01Name, $columns)
    {
        $fixedLeft = $this->getFixedLeftOrRight($column, $index, "left", "th");
        $fixedRight = $this->getFixedLeftOrRight($column, $index, "right", "th");
        $isLastColumn = ($index == sizeof($columns) - 1);
        $renderer = $column['renderer'] ?? "_no_renderer_";
        // $rendererUnit = $column['rendererUnit'] ?? "_no_unit_";
        $dataIndex = $column['dataIndex'];
        $columnDivClass = $column['columnDivClass'] ?? '';
        $columnDivStyle = $column['columnDivStyle'] ?? [];
        $columnName = $column['column_name'] ?? $dataIndex;
        $columnType = $column['column_type'] ?? "";
        $width = $column['width'] ?? "";
        $colspan = $column['colspan'] ?? 1;
        $title = $column['title'] ?? Str::headline($column['dataIndex']);
        $subTitle = $column['subTitle'] ?? null;
        if ($subTitle) $title .= "<hr/><i>" . $subTitle . "</i>";
        if (isset($column['tooltip'])) {
            $tooltip = $column['tooltip'];
        } else {
            $tooltip = "Column details:\n";
            $tooltip .= "+ Index: $index\n";
            $tooltip .= "+ DataIndex: $dataIndex\n";
            $tooltip .= "+ ColumnName: $columnName\n";
            $tooltip .= "+ Renderer: $renderer\n";
            $tooltip .= "+ Width: $width\n";
            $tooltip .= "+ Colspan: $colspan\n";
            $tooltip .= "+ Took: {$elapse}ms";
        }

        $iconJson = $columnType === 'json' ? '<br/><i title="JSON format" class="fa-duotone fa-brackets-curly"></i>' : "";
        if ($columnType === 'json') $title .= $iconJson;
        // if (env('SHOW_ELAPSE'))  $title .= "<br/><span title='Elapse time'>({$elapse}ms)</span>";
        // if ((env('SHOW_ELAPSE') && !app()->isProduction()) || (in_array(CurrentUser::id(), [35, 38]))) $title .= "<br/><span title='Elapse time'>({$elapse}ms)</span>";
        $rotate45Width = $this->rotate45Width;
        $rotate45Height = $this->rotate45Height ?: (($this->rotate45Width) ? $rotate45Width - 100 : false);

        $thStyleStr = $this->getStyleStr([
            'aString753' => 1,
            'width' => ($column['width'] ?? 100) . "px",
            'height' => $rotate45Height . "px",
        ]);

        $divStyleStr = $this->getStyleStr([
            'width' => $rotate45Width . "px",
            ...$columnDivStyle,
        ]);

        $classTh45 = ($this->rotate45Width) ? "rotated-title-left-th" : "";
        $classDiv45 = ($this->rotate45Width) ? "rotated-title-left-div-{$this->rotate45Width} text-left" : "";
        $borderRight = $isLastColumn ? "" : "border1 border-r";
        $borderRight = ($this->rotate45Width) ? "" : $borderRight;
        $tinyText = $this->noCss ? "text-xs" : "";
        $colspanStr = ($colspan > 1) ? "colspan=$colspan" : "";
        $hiddenStr = $hidden ? "hidden" : "";
        $borderColor = $this->noCss ? "border-gray-400" : $this->borderColor;
        $th = "";
        $th .= "<th id='{$table01Name}_th_{$columnName}' $colspanStr $thStyleStr ";
        $th .= "class='$fixedLeft $fixedRight bg-gray-100 px-4 py-3 border-b $borderColor $borderRight $classTh45 $hiddenStr' ";
        $th .= "title='$tooltip' ";
        $th .= ">";
        $th .= "<div class='$classDiv45 $tinyText $columnDivClass text-gray-700 dark:text-gray-300' $divStyleStr>";
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

    private function getColumnRendered($columns, $timeElapse, $table01Name)
    {
        $columnsRendered = [];
        $skippedDueToColspan = $this->getSkippedDueToColspan($columns);
        $columns = array_values($columns);
        foreach ($columns as $index => $column) {
            if (empty($column)) continue;
            // if ($skippedDueToColspan[$index]) continue;
            $hidden = $this->isInvisible($column);
            $hidden = $hidden || $skippedDueToColspan[$index];
            // if (!$this->isInvisible($column)) {
            $dataIndex = $column['dataIndex'];
            $elapse = $timeElapse[$dataIndex] ?? 0;
            $columnsRendered[] = $this->makeTh($column, $index, $elapse, $hidden, $table01Name, $columns);
            // }
        }
        $columnsRendered = join("", $columnsRendered);
        return $columnsRendered;
    }

    private function makeNoColumn($columns)
    {
        $columnNo = [
            "title" => "No.",
            "renderer" => "no.",
            "dataIndex" => "auto.no.",
            'align' => 'center',
            "width" => '50',
            "fixed" => "left",
        ];
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
        $borderColor = $this->borderColor;
        foreach ($columns as $index => $column) {
            if ($this->isInvisible($column)) continue;
            $fixedLeft = $this->getFixedLeftOrRight($column, $index, "left", "th");
            $fixedRight = $this->getFixedLeftOrRight($column, $index, "right", "th");
            $styleStr = $this->getStyleStr(['width' => ($column['width'] ?? 100) . "px", 'a' => 2]);
            $dataIndex = $column['dataIndex'];
            $borderR = $index < (sizeof($columns) - 1) ? 'border-r' : "";
            if (isset($dataHeader[$dataIndex])) {
                if (is_object($dataHeader[$dataIndex])) {
                    $cell_class = $dataHeader[$dataIndex]->cell_class ?? "";
                    $cell_title = $dataHeader[$dataIndex]->cell_title ?? "";
                    $th[] = "<th $styleStr class='$fixedLeft $fixedRight py-1 $borderR border-b $borderColor bg-gray-100 $cell_class' title='$cell_title'>" . $dataHeader[$dataIndex]->value . "</th>";
                } else {
                    $th[] = "<th $styleStr class='$fixedLeft $fixedRight py-1 $borderR border-b $borderColor bg-gray-100'>" . $dataHeader[$dataIndex] . "</th>";
                }
            } else {
                $th[] = "<th $styleStr class='$fixedLeft $fixedRight py-1 $borderR border-b $borderColor bg-gray-100' dataIndex='" . $dataIndex . "'></th>";
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
