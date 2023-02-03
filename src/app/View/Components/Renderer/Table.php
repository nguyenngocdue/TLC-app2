<?php

namespace App\View\Components\Renderer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class Table extends Component
{
  use TableTraitColumns;
  use TableTraitRows;

  public function __construct(
    private Request $request,
    private $tableName = 'table00',
    private $columns = null,
    private $dataSource = null,
    private $dataHeader = null,
    private $headerTop = 0,
    private $showNo = false,
    private $showNoR = false,
    private $groupBy = false,
    private $groupByLength = 1,
    private $header = "",
    private $footer = "",
    private $maxH = 40,
    //Editable MODE
    private $model = null,
    // private $editable = false,
  ) {
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
    if (empty($columns)) return Blade::render("<x-feedback.alert type='warning' message='Columns attribute is an empty array.' />");

    $columns = $this->makeNoColumn($columns);
    $dataSource = $this->dataSource;
    $hasPaging = (is_object($dataSource) && method_exists($dataSource, 'links') && !empty($dataSource));

    return view("components.renderer.table", [
      'columns' => $columns,
      'dataSource' => $dataSource,
      'headerRendered' => $this->makeThHeader($columns, $this->dataHeader),
      'headerTop' => $this->headerTop,
      'columnsRendered' => $this->getColumnRendered($columns),
      'tr_td' => $this->makeTrTd($columns, $dataSource),
      'showing' => $hasPaging ? $dataSource->appends($this->request->toArray())->links('dashboards.pagination.showing') : "",
      'pagination' => $hasPaging ? $dataSource->links('dashboards.pagination.pagination') : "",
      'header' => $this->header,
      'footer' => $this->footer,
      'colgroup' => $this->makeColGroup($columns),
      'maxH' => $this->maxH,
    ]);
  }
}
