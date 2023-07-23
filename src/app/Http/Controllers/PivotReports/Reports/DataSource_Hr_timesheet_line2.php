<?php

namespace App\Http\Controllers\PivotReports\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\Reports\Hr_timesheet_line_100;
use App\Http\Controllers\Reports\TraitFunctionsReport;
use App\Http\Controllers\Reports\TraitModeParamsReport;
use App\Http\Controllers\Reports\TraitUpdateParamsReport;
use App\Http\Controllers\TraitLibPivotTableDataFields2;
use App\Http\Controllers\Workflow\LibReports;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\PivotReport;
use App\Utils\Support\StringPivotTable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class DataSource_Hr_timesheet_line2 extends Controller
{
    use TraitModeParamsReport;
    use TraitLibPivotTableDataFields2;
    use TraitUpdateParamsReport;
    use TraitFunctionsReport;
    use TraitMenuTitle;
    use TraitChangeDataPivotTable2;

    protected $modeType = '';
    protected $mode = '010';
    protected $tableTrueWidth = true;

    public function getType()
    {
        return "dashboard";
    }


    public function getDataSource1($modeParams)
    {
        $primaryData = (new Hr_timesheet_line_100())->getDataSource($modeParams);
        return $primaryData;
    }


    protected function getParamColumns($dataSource, $modeType)
    {
        $filters = $this->getDataFields($dataSource, $modeType)['filters'];
        $colParams = [];
        foreach ($filters as $key => $values) {
            $dataIndex = $key;
            if (isset($values->multiple)) {
                $dataIndex = 'many_' . $key;
            }
            $a = [];
            if ($dataIndex === 'picker_date') {
                $a = ['renderer' => 'picker_date'];
            }
            $colParams[] = [
                'title' => $values->title ?? ucwords(str_replace('_', ' ', $key)),
                'allowClear' => $values->allowClear ?? false,
                'multiple' => $values->multiple ?? false,
                'dataIndex' => $dataIndex,
            ] + $a;
        }
        // dd($colParams);
        return $colParams;
    }

    protected function getPageParam($typeReport, $entity)
    {
        $settings = CurrentUser::getSettings();
        if (!isset($settings[$entity])) return 10;
        if (isset($settings[$entity][strtolower($typeReport)]['per_page'])) {
            $pageLimit = $settings[$entity][strtolower($typeReport)]['per_page'];
            return $pageLimit;
        }
        return 10;
    }

    private function paginateDataSource($dataSource, $pageLimit)
    {
        $page = $_GET['page'] ?? 1;
        $dataSource = (new LengthAwarePaginator($dataSource->forPage($page, $pageLimit), $dataSource->count(), $pageLimit, $page))->appends(request()->query());
        return $dataSource;
    }

    private function makeTableColumnsWhenEmptyData($modeType)
    {
        $rowFields = $this->getDataFields([], $modeType)['row_fields'];
        $cols = [];
        foreach ($rowFields as $key => $values) {
            $cols[] = [
                'title' => isset($values->title) ?
                    ucfirst(StringPivotTable::retrieveStringBeforeString($values->title, '_')) :
                    ucfirst(StringPivotTable::retrieveStringBeforeString($key, '_')),
                'dataIndex' => $key,
            ];
        }
        return $cols;
    }

    private function triggerDataFollowManagePivot($linesData, $modeParams)
    {
        $fn = (new DataPivotTable2);
        $data = $fn->makeDataPivotTable($linesData, $this->modeType, $modeParams);
        return $data;
    }

    private function makeModeTitleReport($routeName)
    {
        $lib = LibReports::getAll();
        $title = $lib[$routeName]['title'] ?? 'Empty Title';
        return $title;
    }

    private function getTable()
    {
        $tableName = CurrentRoute::getCurrentController();
        $tableName = substr($tableName, 0, strrpos($tableName, "_"));
        $tableName = strtolower(Str::plural($tableName));
        return $tableName;
    }

    public function index(Request $request)
    {
        $input = $request->input();
        $modeType = $this->modeType;

        $routeName = $request->route()->action['as'];
        $entity = CurrentPathInfo::getEntityReport($request);
        $typeReport = CurrentPathInfo::getTypeReport($request);
        // dump($entity, $typeReport);
        $modeParams = $this->getModeParams($request);
        $pageLimit = $this->getPageParam($typeReport, $entity);

        if (!$request->input('page') && !empty($input)) {
            return $this->updateParams($request, $modeParams);
        }
        $modeReport = $this->makeModeTitleReport($routeName);

        $linesData = $this->getDataSource1($modeParams);
        [$dataOutput, $tableColumns, $tableDataHeader] = $this->triggerDataFollowManagePivot($linesData, $modeParams);
        // $linesData = collect(array_slice($linesData->toArray(), 0, 10));

        if (PivotReport::isEmptyArray($dataOutput)) {
            $paramColumns = $this->getParamColumns($dataOutput, $modeType);
            $tableColumns = $this->makeTableColumnsWhenEmptyData($modeType);
            return view("reports.report-pivot", [
                'modeReport' => $modeReport,
                'modeParams' => $modeParams,
                'currentMode' => $this->mode,
                'paramColumns' => $paramColumns,
                'routeName' => $routeName,
                'typeReport' => $typeReport,
                'entity' => $entity,
                'pageLimit' => $pageLimit,
                'tableDataSource' => [],
                'tableColumns' => $tableColumns,
                'tableDataHeader' => [],
                'topTitle' => $this->getMenuTitle(),
                'tableTrueWidth' => $this->tableTrueWidth,
            ]);
        }

        // Pivot data before render 
        $paramColumns = $this->getParamColumns($dataOutput, $modeType);
        $dataSource = $this->changeValueData($dataOutput);
        $dataSource = $this->paginateDataSource($dataSource, $pageLimit);

        return view("reports.report-pivot", [
            'modeReport' => $modeReport,
            'modeParams' => $modeParams,
            'currentMode' => $this->mode,
            'paramColumns' => $paramColumns,
            'routeName' => $routeName,
            'typeReport' => $typeReport,
            'entity' => $entity,
            'pageLimit' => $pageLimit,
            'tableDataSource' => $dataSource,
            'tableColumns' => $tableColumns,
            'tableDataHeader' => $tableDataHeader,
            'topTitle' => $this->getMenuTitle(),
            'tableTrueWidth' => $this->tableTrueWidth,
        ]);
    }

    public function exportCSV(Request $request)
    {
        $entity = CurrentPathInfo::getEntityReport($request, '_ep');
        $modeParams = $this->getModeParams($request, '_ep');
        $linesData = $this->getDataSource1($modeParams);
        // Pivot data before render 
        [$dataOutput, $tableColumns,] = $this->triggerDataFollowManagePivot($linesData, $modeParams);
        [$columnKeys, $columnNames] = $this->makeColumnsPivotTable($dataOutput, $modeParams, $tableColumns);
        $rows = $this->makeRowsFollowColumns($dataOutput, $columnKeys);
        $fileName = $entity . '_' . date('d:m:Y H:i:s') . '.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $callback = function () use ($rows, $columnKeys, $columnNames) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columnNames);
            $array = [];
            foreach ($rows as $row) {
                foreach ($columnKeys as $key) {
                    $array[$key] = $row[$key] ?? '';
                }
                fputcsv($file, $array);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
