<?php

namespace App\View\Components\Renderer;

use App\Utils\ClassList;
use App\View\Components\Renderer\Table\TableTraitColumns;
use App\View\Components\Renderer\Table\TableTraitCommon;
use App\View\Components\Renderer\Table\TableTraitFooter;
use App\View\Components\Renderer\Table\TableTraitRows;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class Table3 extends Component
{
  use TableTraitCommon;
  use TableTraitColumns;
  use TableTraitRows;
  use TableTraitFooter;
  // public static $borderColor = 'border-gray-600';
  public static $table11NameCounter = 11;

  public function __construct(
    // private Request $request,
    // private $tableName = 'table01',
    private $columns = null,
    private $dataSource = null,
    private $tableDebug = false,
    private $showNo = false,
    private $dataHeader = [],
    // private $showNoR = false,
    // private $groupBy = false,
    // private $groupByLength = 1,
    // private $groupKeepOrder = false,
    private $header = "",
    private $footer = "",

    private $maxH = 640,

    // // private $minH = 40,
    // //Editable MODE
    // private $model = null,
    // // private $editable = false,
    private $rotate45Width = false,
    private $rotate45Height = false,
    // private $noCss = false,
    private $showPaginationTop = false,
    private $showPaginationBottom = false,
    private $topLeftControl = null,
    private $topCenterControl = null,
    private $topRightControl = null,
    private $bottomLeftControl = null,
    private $bottomCenterControl = null,
    private $bottomRightControl = null,
    private $tableTrueWidth = false,
    private $editable = false,
    private $animationDelay = 0,
    private $rowHeight = 45,

    private $orderable = false,
    private $duplicatable = false,
    private $deletable = false,

    // private $numberOfEmptyLines = 0,
    // private $lineIgnoreNo = 0,
    // private $borderColor = 'border-gray-300',

    // private $headerTop = null, // OBSOLETE due to applyTopFor2ndHeader
  ) {}

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render()
  {
    $classList = [
      'text' => ClassList::TEXT,
      'textarea' => ClassList::TEXTAREA,
      'dropdown' => ClassList::DROPDOWN,
      'toggle' => ClassList::TOGGLE,
      'button' => ClassList::BUTTON,
      'toggle_checkbox' => ClassList::TOGGLE_CHECKBOX,
      'dropdown_fake' => ClassList::DROPDOWN_FAKE,
      // 'radio_checkbox' => ClassList::RADIO_CHECKBOX,
      // 'radio_group' => ClassList::RADIO_GROUP,
      // 'button2' => ClassList::BUTTON2,

    ];

    $tableName = 'table' . self::$table11NameCounter++;

    $params = [
      'tableName' => $tableName,
      'columns' => $this->columns,
      'dataSource' => $this->dataSource,
      'dataHeader' => $this->dataHeader,
      'tableDebug' => $this->tableDebug,
      'tableTrueWidth' => $this->tableTrueWidth,

      // 'headerTop' => $this->headerTop, // OBSOLETE due to applyTopFor2ndHeader
      'header' => $this->header,
      'footer' => $this->footer,
      'maxH' => $this->maxH,
      'showNo' => $this->showNo,

      'showPaginationTop' => $this->showPaginationTop,
      'showPaginationBottom' => $this->showPaginationBottom,
      'topLeftControl' => $this->topLeftControl,
      'topCenterControl' => $this->topCenterControl,
      'topRightControl' => $this->topRightControl,

      'bottomLeftControl' => $this->bottomLeftControl,
      'bottomCenterControl' => $this->bottomCenterControl,
      'bottomRightControl' => $this->bottomRightControl,

      'rotate45Width' => $this->rotate45Width,
      'rotate45Height' => $this->rotate45Height,

      // 'editable' => $this->editable,
      'classList' => $classList,
      'animationDelay' => $this->animationDelay,
      'rowHeight' => $this->rowHeight,

      'orderable' => $this->orderable,
      'duplicatable' => $this->duplicatable,
      'deletable' => $this->deletable,
    ];
    // Log::info($params);
    return view("components.renderer.table3", $params);
  }

  // public function render1()
  // {
  //   $columns = $this->columns;
  //   if (is_null($columns)) return Blade::render("<x-feedback.alert type='warning' message='Columns attribute is missing.' />");
  //   if (!is_array($columns)) return Blade::render("<x-feedback.alert type='warning' message='Props file is missing.' />");
  //   if (empty($columns)) return Blade::render("<x-feedback.alert type='warning' message='Columns attribute is an empty array.' />");

  //   $columns = $this->makeNoColumn($columns);
  //   $columns = $this->hideColumns($columns);
  //   $columns = array_values($columns);
  //   $dataSource = $this->dataSource;
  //   $hasPaging = (is_object($dataSource) && method_exists($dataSource, 'links') && !empty($dataSource));

  //   //makeFooter has to called before adding numberOfEmptyLines empty lines 
  //   $footerRendered = $this->makeFooter($columns, $this->tableName, $dataSource);

  //   if ($this->noCss && $this->numberOfEmptyLines) {
  //     // dump($dataSource);
  //     for ($i = 0; $i < $this->numberOfEmptyLines; $i++) {
  //       if (is_array($dataSource)) array_push($dataSource, []);
  //       else $dataSource->push([]);
  //     }
  //   }

  //   $tr_td = $this->makeTrTd($columns, $dataSource, $this->tableDebug, $this->tableName);

  //   $headerRendered = $this->makeTable2ndThead($columns, $this->dataHeader);

  //   $columnsRendered =  $this->getColumnRendered($columns, $this->timeElapse, $this->tableName);
  //   $showing = ($hasPaging && !$this->editable) ? $dataSource->appends($this->request->toArray())->links('dashboards.pagination.showing') : "";
  //   $pagination = $hasPaging ? $dataSource->links('dashboards.pagination.pagination') : "";

  //   $colgroup = $this->makeColGroup($columns);
  //   $tableWidth = $this->tableTrueWidth ? $this->getTableWidth($columns) : "";

  //   // return "TABLE";

  //   return view("components.renderer.table3", [
  //     'tableName' => $this->tableName,
  //     'columns' => $columns,
  //     'dataSource' => $dataSource,
  //     'headerRendered' => $headerRendered,
  //     'footerRendered' => $footerRendered,
  //     'headerTop' => $this->headerTop,
  //     'columnsRendered' => $columnsRendered,
  //     'tr_td' => $tr_td,
  //     'showing' => $showing,
  //     'pagination' => $pagination,
  //     'header' => $this->header,
  //     'footer' => $this->footer,
  //     'colgroup' => $colgroup,
  //     'tableWidth' => $tableWidth,
  //     'maxH' => $this->maxH ? "max-h-[{$this->maxH}rem]" : "",
  //     // 'minH' => $this->minH ? "min-h-[{$this->minH}rem]" : "",
  //     'tableDebug' => $this->tableDebug,
  //     'trClassList' => 'bg-gray-100 text-center text-xs text-xs-vw font-semibold tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300',
  //     'noCss' => $this->noCss,
  //     'showPaginationTop' => $this->showPaginationTop,
  //     'showPaginationBottom' => $this->showPaginationBottom,
  //     'topLeftControl' => $this->topLeftControl,
  //     'topCenterControl' => $this->topCenterControl,
  //     'topRightControl' => $this->topRightControl,
  //     'bottomLeftControl' => $this->bottomLeftControl,
  //     'bottomCenterControl' => $this->bottomCenterControl,
  //     'bottomRightControl' => $this->bottomRightControl,
  //     'numberOfEmptyLines' => $this->numberOfEmptyLines,
  //     'lineIgnoreNo' => $this->lineIgnoreNo,
  //     'borderColor' => $this->borderColor,
  //   ]);
  // }
}
