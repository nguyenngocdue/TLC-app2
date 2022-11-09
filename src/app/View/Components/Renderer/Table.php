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
  public function __construct(private $columns = [], private $dataSource = [])
  {
    // Log::info($columns);
    // Log::info($dataSource);
  }

  private function applyRender($render, $rawData, $attributes, $dataLine)
  {
    array_walk($attributes, fn (&$value, $key) => $value = isset($dataLine[$value]) ? "$key='$dataLine[$value]'" : "");
    $attributeRendered =  join(" ", $attributes);
    $output = "<x-renderer.{$render} $attributeRendered>$rawData</x-renderer.{$render}>";
    return Blade::render($output);
  }

  private function makeColumn($column)
  {
    $render = $column['render'] ?? "_";
    return "<th class='px-4 py-3' title=\"{$column['dataIndex']} / {$render}\">{$column['title']}</th>";
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render()
  {
    $columns = $this->columns;
    $columnsRendered = join("", array_map(fn ($column) => $this->makeColumn($column), $columns));

    $trs = [];
    $dataSource = $this->dataSource;
    if (sizeof($dataSource) > 0) {
      foreach ($dataSource as $dataLine) {
        $tds = [];
        foreach ($columns as $column) {
          $dataIndex = $column['dataIndex'];
          $render = $column['render'] ?? false;
          $attributes = $column['attributes'] ?? [];
          $rawData = $dataLine[$dataIndex] ?? ""; //"<strong>$dataIndex</strong> not found";
          $rendered = $render ? $this->applyRender($render, $rawData, $attributes, $dataLine) : $rawData;
          $tds[] = "<td class='px-4 py-3'>" . $rendered . "</td>";
        }
        $trs[] = "<tr class='text-gray-700 dark:text-gray-400'>" . join("", $tds) . "</tr>";
      }
      $trtd = join("", $trs);
    } else {
      $colspan = sizeof($columns);
      $trtd = "<tr><td colspan=$colspan>" . Blade::render("<x-renderer.emptiness/>") . "</td></tr>";
    }

    return view("components.renderer.table")->with(compact('columnsRendered', 'trtd'));
  }
}
