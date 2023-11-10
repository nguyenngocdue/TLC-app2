<?php

namespace App\View\Components\Renderer\Report;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Reports\DataPivotTable2;
use App\Http\Controllers\Reports\TraitChangeDataPivotTable2;
use App\Http\Controllers\Reports\TraitFunctionsReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Http\Controllers\Reports\TraitUpdateParamsReport;
use App\Http\Controllers\Reports\TraitLibPivotTableDataFields2;
use App\Http\Controllers\Workflow\LibPivotTables2;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use App\Utils\Support\StringPivotTable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class PivotTable extends Component
{

    use PivotReportColumn2;
    use TraitParamsSettingReport;
    use TraitUpdateParamsReport;
    use TraitFunctionsReport;
    use TraitMenuTitle;
    use TraitLibPivotTableDataFields2;
    use TraitChangeDataPivotTable2;

    public function __construct(
        private $modeType = '',
        private $dataSource = [],
        private $params = [],
        private $pageLimit = 10,
        protected $tableTrueWidth = false,
        protected $tableColumns = [],
        protected $tableDataHeader = [],
        protected $maxH = false,

        protected $showPaginationTop= true,
        protected $topLeftControl= '' ,
        protected $topCenterControl='' ,
        protected $topRightControl='' ,


    ) {
    }

    private function triggerDataFollowManagePivot($linesData, $modeType, $params)
    {
        $fn = (new DataPivotTable2);
        $data = $fn->makeDataPivotTable($linesData, $modeType, $params);
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
        $insColRowField = $libs['insert_column_row_fields'];
        $modeType = $this->modeType;
        $isRenderRowField = $libs['is_render_row_fields'][$modeType] ?? null;
        $data = is_object($linesData) ? $linesData->toArray() : $linesData;
        $columnsData = [];
        if ($isRenderRowField) {
            foreach ($rowFields as $key => $rowField) {
                $dataIndex = $key;
                if (isset($rowField->column)) {
                    $dataIndex = StringPivotTable::makeFieldFromString($rowField->column);
                }
                $columnsData[] = [
                    'dataIndex' => $dataIndex,
                    'width' =>  $rowField->width ?? 140,
                    'align' => $rowField->align ?? 'right',
                    'title' => $rowField->title ?? ucwords(str_replace('_id', '', $key)),
                    'footer' => $rowField->footer ?? null,
                    'fixed' => $rowField->fixed ?? null,
                ];
            }
        } else {
            foreach (((array)reset($data)) as $key => $value) {
                $title = [];
                $align = 'right';
                $fixed = isset($rowFields[$key]->fixed) ? $rowFields[$key]->fixed :null;

                if (isset($rowFields[$key]->title) && ($t = $rowFields[$key]->title)) $title['title'] = $t;
                if (isset($rowFields[$key]->align) && ($align = $rowFields[$key]->align)) $align = $align;
                $dataIndex = isset($rowFields[$key]->column) ? str_replace('_id', '_name', $key) 
                            : (isset($isRenderRowField->is_dataSource) && $isRenderRowField->is_dataSource ? null : $key);
                $columnsData[] = [
                    'dataIndex' => $dataIndex,
                    'width' => 140,
                    'align' => $align,
                    'fixed' => $fixed,
                ] + $title;
            }
        }

        $columnsData = self::insertColumns($rowFields,$insColRowField, $columnsData);
        // dd($columnsData);
        return $columnsData;
    }

    private function generateFields($tables, $dataOutput)
    {
        foreach ($dataOutput as $k1 => $item) {
            foreach ($tables as $k2 => $model) {
                $str = Str::singular($k2);
                if (isset($item[$str . '_id'])) {
                    $item[$str . '_name'] = $model[$item[$str . '_id']]->name;
                    $dataOutput[$k1] = $item;
                }
            }
        }
        return $dataOutput;
    }

    public function render()
    {
        $linesData = $this->dataSource;
        // dump($linesData);
        $params = $this->params;
        $pageLimit = $this->pageLimit;
        $modeType = $this->modeType;

        $dataFields = $this->getDataFields($modeType);

        $isRawData = false;
        if (isset($dataFields['is_raw'][$modeType])) {
            $isRawData = $dataFields['is_raw'][$modeType]->is_dataSource;
        }
        $tableDataHeader = $this->tableDataHeader;

        if ($modeType && !$isRawData) {
            [$dataOutput, $tableColumns, $tableDataHeader] = $this->triggerDataFollowManagePivot($linesData, $modeType, $params);
            // dd($dataOutput);
            if (PivotReport::isEmptyArray($dataOutput)) {
                $tableColumns = $this->makeTableColumnsWhenEmptyData($modeType);
            }
        } elseif (!$modeType) {
            $tableColumns = $this->tableColumns ? $this->tableColumns : $this->makeTableColumnsWhenEmptyModeType($linesData);
            $dataOutput = $dataOutput ?? $linesData;
        }

        $libs = LibPivotTables2::getFor($this->modeType);
        if ($isRawData) {
            $tableColumns = $this->makeTableColumnsWhenRenderRawData($linesData, $libs);
            $tableName = $this->getTablesNamesFromLibs($libs);
            $tables = $this->getDataFromTables($tableName);
            $dataOutput = $this->generateFields($tables, $linesData);
        }

        $dataOutput = $this->changeValueData($dataOutput, $isRawData, $linesData, $params);
        // $dataOutput = Report::formatNumbersInDataSource($dataOutput, $decimal = 2);
        $dataRender = $this->paginateDataSource($dataOutput, $pageLimit);
        if (!empty($this->tableColumns)) {
            $tableColumns = $this->tableColumns;
        }
        // dump($dataRender);
        return view("components.renderer.report.pivot-table", [
            'tableDataSource' => $dataRender,
            'tableColumns' => $tableColumns,
            'tableDataHeader' => $tableDataHeader,
            'tableTrueWidth' => $this->tableTrueWidth,
            'maxH' => $this->maxH,
            
            'showPaginationTop'=>$this->showPaginationTop,
            'topLeftControl'=> $this->topLeftControl ,
            'topCenterControl'=>$this->topCenterControl, 
            'topRightControl'=>$this->topRightControl ,
        ]);
    }
}
