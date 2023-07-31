<?php

namespace App\View\Components\Renderer\Report;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\DataPivotTable2;
use App\Http\Controllers\Reports\TraitChangeDataPivotTable2;
use App\Http\Controllers\Reports\TraitFunctionsReport;
use App\Http\Controllers\Reports\TraitModeParamsReport;
use App\Http\Controllers\Reports\TraitUpdateParamsReport;
use App\Http\Controllers\Reports\TraitLibPivotTableDataFields2;
use App\Utils\Support\PivotReport;
use App\Utils\Support\StringPivotTable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class PivotTable extends Component
{

    use TraitLibPivotTableDataFields2;
    use PivotReportColumn2;
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

    ) {
    }

    private function triggerDataFollowManagePivot($linesData, $modeType, $modeParams)
    {
        $fn = (new DataPivotTable2);
        $data = $fn->makeDataPivotTable($linesData, $modeType, $modeParams);
        return $data;
    }

    private function paginateDataSource($dataSource, $pageLimit)
    {
            $page = $_GET['page'] ?? 1;
            // Convert array to a collection
            if (!($dataSource instanceof Collection)) {
                $dataSource = collect($dataSource);
            }
            $dataSource = (new LengthAwarePaginator($dataSource->forPage($page, $pageLimit), $dataSource->count(), $pageLimit, $page))
                ->appends(request()->query());
            return $dataSource;

    }

    private function makeTableColumnsWhenEmptyData($modeType)
    {
        $rowFields = $this->getDataFields([], $modeType)['row_fields'];
        $cols = [];
        foreach ($rowFields as $key => $values) {
            $cols[] = [
                'title' => isset($values->title) ?
                    ucfirst(StringPivotTable::retrieveStringBySign($values->title, '_')) :
                    ucfirst(StringPivotTable::retrieveStringBySign($key, '_')),
                'dataIndex' => $key,
            ];
        }
        return $cols;
    }

    private function makeTableColumnsWhenEmptyModeType($linesData)
    {
        $data = is_object($linesData) ? $linesData->toArray() : $linesData;
        $columns = [];
        foreach (array_keys((array)reset($data)) as $key) {
            $columns[] = [
                'title' => ucwords(str_replace(' id', '', str_replace('_', ' ', $key))),
                'dataIndex' => $key,
                'width' => 100,
            ];
        }
        return $columns;
    }


    public function render()
    {
        $linesData = $this->dataSource;
        $modeParams = $this->modeParams;
        $pageLimit = $this->pageLimit;
        $modeType = $this->modeType;
        if ($modeType) {
            [$dataOutput, $tableColumns, $tableDataHeader] = $this->triggerDataFollowManagePivot($linesData, $modeType, $modeParams);
            if (PivotReport::isEmptyArray($dataOutput)) {
                $tableColumns = $this->makeTableColumnsWhenEmptyData($modeType);
            }
        } else {
            $tableColumns = $this->makeTableColumnsWhenEmptyModeType($linesData);
            $dataOutput = $dataOutput ?? $linesData;
        }
        $dataOutput = $this->changeValueData($dataOutput);
        $dataRender = $this->paginateDataSource($dataOutput, $pageLimit);

        // dd($linesData, $dataOutput);
        return view("components.renderer.report.pivot-table", [
            'tableDataSource' => $dataRender,
            'tableColumns' => $tableColumns,
            'tableDataHeader' => $tableDataHeader ?? [],
            'tableTrueWidth' => $this->tableTrueWidth,
        ]);
    }
}
