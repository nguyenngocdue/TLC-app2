<?php

namespace App\View\Components\Renderer\Report;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\DataPivotTable2;
use App\Http\Controllers\Reports\TraitChangeDataPivotTable2;
use App\Http\Controllers\Reports\TraitFunctionsReport;
use App\Http\Controllers\Reports\TraitModeParamsReport;
use App\Http\Controllers\Reports\TraitUpdateParamsReport;
use App\Http\Controllers\Reports\TraitLibPivotTableDataFields2;
use App\Http\Controllers\Workflow\LibPivotTables2;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use App\Utils\Support\StringPivotTable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class PivotTable extends Component
{

    use PivotReportColumn2;
    use TraitModeParamsReport;
    use TraitUpdateParamsReport;
    use TraitFunctionsReport;
    use TraitMenuTitle;
    use TraitLibPivotTableDataFields2;
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
        $rowFields = $this->getDataFields($modeType)['row_fields'];
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
        // dump($columns);
        return $columns;
    }
    private function makeTableColumnsWhenRenderRawData($linesData, $libs)
    {
        $rowFields = $libs['row_fields'];
        $data = is_object($linesData) ? $linesData->toArray() : $linesData;
        $columns = [];
        foreach (((array)reset($data)) as $key => $value) {
            $title = [];
            $align = 'right';
            if (isset($rowFields[$key]->title) && ($t = $rowFields[$key]->title)) $title['title'] = $t;
            if (isset($rowFields[$key]->align) && ($align = $rowFields[$key]->align)) $align = $align;
            $columns[] = [
                'dataIndex' => $key,
                'width' => 130,
                'align' => $align,
            ] + $title;
        }
        $columns = Report::sortByKey($columns, 'dataIndex');
        return $columns;
    }

    private function updateRawData($linesData, $libs)
    {
        $rowFields = $libs['row_fields'];
        $tableName = $this->getTablesNamesFromLibs($libs);
        $tables = $this->getDataFromTables($tableName);

        foreach ($linesData as &$lines) {
            foreach ($rowFields  as $key => $field) {
                if (isset($lines->$key)) {
                    [$tableName, $attr] = explode('.', $field->column, 2);
                    $id = $lines->$key;
                    $nameOfField = $tables[$tableName][$id]->{$attr};
                    $tooltip = $tables[$tableName][$id]->description ?? 'ID: ' . $id;
                    $lines->$key = (object)[
                        'value' => $nameOfField ?? $id,
                        'cell_title' => $tooltip,
                    ];
                }
            }
        }
        return $linesData;
    }


    public function render()
    {
        $linesData = $this->dataSource;
        $modeParams = $this->modeParams;
        $pageLimit = $this->pageLimit;
        $modeType = $this->modeType;

        $dataFields = $this->getDataFields($modeType);

        $isDataSource = false;
        if (isset($dataFields['is_raw'][$modeType])) {
            $isDataSource = $dataFields['is_raw'][$modeType]->is_dataSource;
        }

        if ($modeType && !$isDataSource) {
            [$dataOutput, $tableColumns, $tableDataHeader] = $this->triggerDataFollowManagePivot($linesData, $modeType, $modeParams);
            if (PivotReport::isEmptyArray($dataOutput)) {
                $tableColumns = $this->makeTableColumnsWhenEmptyData($modeType);
            }
        } else {
            $libs = LibPivotTables2::getFor($this->modeType);
            $linesData = $this->updateRawData($linesData, $libs);
            $tableColumns = $this->makeTableColumnsWhenRenderRawData($linesData, $libs);
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
