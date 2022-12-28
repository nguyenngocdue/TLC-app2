<?php

namespace App\View\Components\Renderer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use ReflectionClass;

class Table extends Component
{
  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct(
    private $columns = null,
    private $dataSource = null,
    private $showNo = false,
    private $showNoR = false,
    private $groupBy = false,
    private $header = "",
    private $footer = "",
    private Request $request,
  ) {
  }

  private function getAttributeRendered($column, $dataLine)
  {
    $attributes = $column['attributes'] ?? [];
    array_walk($attributes, fn (&$value, $key) => $value = isset($dataLine[$value]) ? "$key='$dataLine[$value]'" : "$key='$value'");
    $attributeRendered = trim(join(" ", $attributes));
    return $attributeRendered;
  }

  private function getRendererParams($column)
  {
    $str = (is_array($column['rendererParam'])) ? json_encode($column['rendererParam']) : $column['rendererParam'];
    return "rendererParam='$str'";
  }

  private function applyRender($renderer, $rawData, $column, $dataLine)
  {
    $name = isset($column['dataIndex']) ? "name='{$column['dataIndex']}[]'" : "";
    // $attributeRender = isset($column['attributes']) ? $this->getAttributeRendered($column, $dataLine) : "";
    $attributeRender = $this->getAttributeRendered($column, $dataLine);
    $typeRender = isset($column['type']) ? "type='{$column['type']}'" : "";
    $sortByRender = isset($column['sortBy']) ? "sortBy='{$column['sortBy']}'" : "";

    $cbbDataSource = $column['cbbDataSource'] ?? ["", "true"];
    $cbbDataSourceRender = $cbbDataSource ? ':cbbDataSource=\'$cbbDataSource\'' : "";
    $dataLineRender = $dataLine ? ':dataLine=\'$dataLine\'' : "";
    $columnRender = $column ? ':column=\'$column\'' : "";
    $cellRender = ':cell=\'$cell\'';
    $rendererParam = isset($column['rendererParam']) ? $this->getRendererParams($column) : "";
    $formatterName = isset($column['formatterName']) ? "formatterName='{$column['formatterName']}'" : "";

    $attributes = "$name $attributeRender $typeRender $cbbDataSourceRender ";
    $attributes .= "$dataLineRender $columnRender $cellRender $rendererParam $formatterName ";
    $attributes .= "$sortByRender ";

    $editable = isset($column['editable']) ? ".editable" : "";
    $tagName = "x-renderer{$editable}.{$renderer}";

    $output = "<$tagName $attributes>$rawData</$tagName>";
    // if ($editable) Log::info($output);
    // Log::info($output);
    // Log::info($column);
    $cell = $dataLine[$column['dataIndex']] ?? "No dataIndex for " . $column['dataIndex']; //No dataIndex for is used in Thumbnail
    return Blade::render($output, [
      'cbbDataSource' => $cbbDataSource,
      'column' => $column,
      'dataLine' => $dataLine,
      'cell' => $cell,
    ]);
  }

  private function makeColumn($column)
  {
    $renderer = $column['renderer'] ?? "_";
    $dataIndex = $column['dataIndex'];
    $title = $column['title'] ?? Str::headline($column['dataIndex']);
    return "<th class='{$dataIndex}_th px-4 py-3' title=\"{$dataIndex} / {$renderer}\">{$title}</th>";
  }

  private function makeTd($columns, $dataLine, $columnCount, $no)
  {
    $tds = [];
    // Log::info($columns);
    foreach ($columns as $index => $column) {
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
            ? $this->applyRender($renderer, $rawData, $column, $dataLine)
            : $rawData;
          break;
      }
      $align = ($column['align'] ?? null) ? "text-" . $column['align'] : "";
      $borderRight = ($index < $columnCount - 1) ? "border-r" : "";
      $tds[] = "<td class='px-1 py-1 $borderRight $align'>" . $rendered . "</td>";
    }
    return $tds;
  }

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

  private function makeColGroup($columns)
  {
    $result = [];
    foreach ($columns as $column) {
      $name = $column['dataIndex'];
      if (isset($column['width'])) {
        $w = $column['width'];
        $result[] = "<col name='$name' style='width: {$w}px'>";
      } else {
        $result[] = "<col name='$name'>";
      }
    }
    return join("", $result);
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
      $tds = $this->makeTd($columns, $dataLine, $columnCount, $start + $no + 1);

      if ($this->groupBy) {
        if (isset($dataLine[$this->groupBy][0])) { //<< this to make sure an item with empty name doesn't crash the app
          $index = strtoupper($dataLine[$this->groupBy][0]);
          if ($index !== $lastIndex) {
            $lastIndex = $index;
            $trs[] = "<tr class='bg-gray-100 '><td class='p-2 text-lg font-bold text-gray-600' colspan=$colspan>{$index}</td></tr>";
          }
        }
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
    if (is_null($columns)) return Blade::render("<x-feedback.alert type='warning' message='Columns attribute is missing.' />");
    if (!is_array($columns)) return Blade::render("<x-feedback.alert type='warning' message='Props file is missing.' />");
    if (empty($columns)) return Blade::render("<x-feedback.alert type='warning' message='Columns attribute is empty.' />");

    // Log::info($this->showNo);
    $columnNo = ["title" => "No.", "renderer" => "no.", "dataIndex" => "auto.no.", 'align' => 'center', "width" => '10'];
    if ($this->showNo) array_unshift($columns, $columnNo);
    if ($this->showNoR) array_push($columns, $columnNo);

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
    // dump($dataSource);
    if (is_object($dataSource) && method_exists($dataSource, 'links') && !empty($dataSource)) {

      $showing = $dataSource->appends($this->request->toArray())->links('dashboards.pagination.showing');
      $pagination = $dataSource->links('dashboards.pagination.pagination');
    }

    $footer = $this->footer;
    $header = $this->header;
    $colgroup = $this->makeColGroup($columns);

    return view("components.renderer.table")->with(compact(
      'columnsRendered',
      'trtd',
      'showing',
      'pagination',
      'columns',
      'dataSource',
      'header',
      'footer',
      'colgroup',
    ));
  }
}
