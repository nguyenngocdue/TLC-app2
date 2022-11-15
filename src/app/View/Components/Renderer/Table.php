<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class Table extends Component
{
  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct(private $columns = [], private $dataSource = null, private $showNo = false)
  {
  }

  private function applyRender($renderer, $rawData, $column, $dataLine)
  {
    $editable = ($column['editable'] ?? false) ? ".editable" : "";
    $name = ($column['dataIndex'] ?? false) ? "name='{$column['dataIndex']}[]'" : "";
    $type = ($column['type'] ?? false) ? "type='{$column['type']}'" : "";
    $rendererParam = ($column['rendererParam'] ?? false) ? "rendererParam='{$column['rendererParam']}'" : "";
    $attributes = $column['attributes'] ?? [];
    $dataSource = $column['dataSource'] ?? [["title" => "", "value" => ""], ["title" => "True", "value" => "true"]];
    $columnRender = $column ? ':column=\'$column\'' : "";
    $dataSourceRender = $dataSource ? ':dataSource=\'$dataSource\'' : "";
    $dataLineRender = $dataLine ? ':dataLine=\'$dataLine\'' : "";

    array_walk($attributes, fn (&$value, $key) => $value = isset($dataLine[$value]) ? "$key='$dataLine[$value]'" : "");
    $attributeRendered = trim(join(" ", $attributes));
    $output = "<x-renderer{$editable}.{$renderer} $name $attributeRendered $type $dataSourceRender $dataLineRender $columnRender $rendererParam>$rawData</x-renderer{$editable}.{$renderer}>";
    // if ($editable) Log::info($output);
    // Log::info($output);
    return Blade::render($output, [
      'dataSource' => $dataSource,
      'column' => $column,
      'dataLine' => $dataLine,
    ]);
  }

  private function makeColumn($column)
  {
    $renderer = $column['renderer'] ?? "_";
    $dataIndex = $column['dataIndex'];
    $title = $column['title'];
    return "<th class='{$dataIndex}_th px-4 py-3' title=\"{$dataIndex} / {$renderer}\">{$title}</th>";
  }

  private function makeTrTd($columns, $dataSource)
  {
    $trs = [];
    $colspan = sizeof($columns);

    if (is_null($dataSource)) return "<tr><td colspan=$colspan>" . Blade::render("<x-feedback.alert type='error' message='DataSource attribute is missing.' />") . "</td></tr>";
    if (empty($dataSource) || (is_object($dataSource) && empty($dataSource->items()))) return "<tr><td colspan=$colspan>" . Blade::render("<x-renderer.emptiness/>") . "</td></tr>";

    foreach ($dataSource as $no => $dataLine) {
      $tds = [];
      foreach ($columns as $column) {
        $renderer = $column['renderer'] ?? false;
        switch ($renderer) {
          case  'no.':
            $rendered = $no + 1;
            break;
          default:
            $dataIndex = $column['dataIndex'];
            $rawData = $dataLine[$dataIndex] ?? ""; //"<strong>$dataIndex</strong> not found";
            $rendered = $renderer ? $this->applyRender($renderer, $rawData, $column, $dataLine) : $rawData;
            break;
        }
        $align = ($column['align'] ?? null) ? "text-" . $column['align'] : "";
        $tds[] = "<td class='px-1 py-1 $align'>" . $rendered . "</td>";
      }
      $bgClass = ($dataLine['row_color'] ?? false) ? "bg-" . $dataLine['row_color'] . "-400" : "";
      $trs[] = "<tr class='hover:bg-gray-100 $bgClass text-gray-700 dark:text-gray-400'>" . join("", $tds) . "</tr>";
      if (isset($dataLine['rowDescription'])) {
        $trs[] = "<tr class='bg-gray-100 '><td class='p-2 text-xs text-gray-600' colspan=$colspan>{$dataLine['rowDescription']}</td></tr>";
      }
    }
    $trtd = join("", $trs);

    return $trtd;
  }
  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render()
  {
    $columns = $this->columns;
    if (!is_array($columns)) return Blade::render("<x-feedback.alert type='error' message='Props file is missing.' />");
    if (empty($columns)) return Blade::render("<x-feedback.alert type='error' message='Columns attribute is missing or empty.' />");

    // Log::info($this->showNo);
    if ($this->showNo) {
      array_unshift($columns, ["title" => "No.", "renderer" => "no.", "dataIndex" => "auto.no.", 'align' => 'center']);
      // Log::info($columns);
    }

    $dataSource = $this->dataSource;
    // Log::info($dataSource);
    $columnsRendered = [];
    array_walk($columns, function ($column, $key) use (&$columnsRendered) {
      $columnsRendered[] = $this->makeColumn($column, $key);
    });
    $columnsRendered = join("", $columnsRendered);
    $trtd = $this->makeTrTd($columns, $dataSource);

    $showing = "";
    $pagination = "";
    if (is_object($dataSource) && !empty($dataSource)) {
      $showing =  $dataSource->links('dashboards.pagination.showing');
      $pagination =  $dataSource->links('dashboards.pagination.template');
    }

    return view("components.renderer.table")->with(compact('columnsRendered', 'trtd', 'showing', 'pagination', 'columns', 'dataSource'));
  }
}
