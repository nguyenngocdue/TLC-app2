<?php

namespace App\View\Components\Renderer\Report;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\PivotReports\Reports\DataPivotTable2;
use App\Http\Controllers\PivotReports\Reports\TraitChangeDataPivotTable2;
use App\Http\Controllers\Reports\TraitFunctionsReport;
use App\Http\Controllers\Reports\TraitModeParamsReport;
use App\Http\Controllers\Reports\TraitUpdateParamsReport;
use App\Http\Controllers\TraitLibPivotTableDataFields2;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class PivotTable extends Component
{

    use TraitLibPivotTableDataFields2;
    use ColumnsPivotReport2;
    use TraitModeParamsReport;
    use TraitUpdateParamsReport;
    use TraitFunctionsReport;
    use TraitMenuTitle;
    use TraitChangeDataPivotTable2;

    public function __construct(
        private $modeType = '',
        private $dataSource = [],
        private $modeParams = [],
        private $pageLimit = 10,
        protected $tableTrueWidth = true,

    ) {}

    private function triggerDataFollowManagePivot($linesData,$modeType, $modeParams)
    {
        $fn = (new DataPivotTable2);
        $data = $fn->makeDataPivotTable($linesData, $modeType, $modeParams);
        return $data;
    }

    private function paginateDataSource($dataSource, $pageLimit)
    {
        $page = $_GET['page'] ?? 1;
        $dataSource = (new LengthAwarePaginator($dataSource->forPage($page, $pageLimit), $dataSource->count(), $pageLimit, $page))->appends(request()->query());
        return $dataSource;
    }

    public function render()
    {
        $linesData = $this->dataSource;
        $modeParams = $this->modeParams;
        $pageLimit = $this->pageLimit;
        $modeType = $this->modeType;
        [$dataOutput, $tableColumns, $tableDataHeader] = $this->triggerDataFollowManagePivot($linesData,$modeType, $modeParams);
        $dataSource = $this->changeValueData($dataOutput);
        $dataSource = $this->paginateDataSource($dataSource, $pageLimit);
        return view("components.renderer.report.pivot-table", [
            'tableDataSource' => $dataSource,
            'tableColumns' => $tableColumns,
            'tableDataHeader' => $tableDataHeader,
            'tableTrueWidth' => $this->tableTrueWidth,
        ]);
    }
}
