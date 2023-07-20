<?php

namespace App\Http\Controllers\PivotReports\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\Reports\Hr_timesheet_line_100;
use App\Http\Controllers\Reports\TraitFunctionsReport;
use App\Http\Controllers\Reports\TraitModeParamsReport;
use App\Http\Controllers\Reports\TraitUpdateParamsReport;
use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\Http\Controllers\Workflow\LibReports;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\PivotReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class DataSource_Hr_timesheet_line extends Controller
{
    use TraitModeParamsReport;
    use TraitLibPivotTableDataFields;
    use TraitUpdateParamsReport;
    use TraitFunctionsReport;
    use TraitMenuTitle;

    protected $modeType = '';
    protected $mode = '010';
    // protected function makeDataPivotTable();
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
        try {
            [,,,,,,,,,,,, $dataFilters] = $this->getDataFields($dataSource, $modeType);
            return $dataFilters;
        } catch (Exception $e) {
            $dataFilters = $this->getDataFields($dataSource, $modeType);
            return $dataFilters;
        }
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

    private function makeTableColumns($data)
    {
        $cols = [];
        foreach (array_values($data) as $values) {
            $cols[] = [
                'title' => $values['title_override'] ?? str_replace('_', ' ', $values['field_index']),
                'dataIndex' => $values['field_index'],
            ];
        }
        return $cols;
    }

    private function triggerDataFollowManagePivot($linesData, $modeParams)
    {
        $fn = (new DataPivotTable);
        $data = $fn->makeDataPivotTable($linesData, $this->modeType, $modeParams);
        return $data;
    }

    private function makeModeTitleReport($routeName)
    {
        $lib = LibReports::getAll();
        $title = $lib[$routeName]['title'] ?? 'Empty Title';
        return $title;
    }

    protected function getTable()
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
        $modeParams = [];
        $modeParams = $this->getModeParams($request);
        $pageLimit = $this->getPageParam($typeReport, $entity);

        if (!$request->input('page') && !empty($input)) {
            return $this->updateParams($request, $modeParams);
        }
        $modeReport = $this->makeModeTitleReport($routeName);

        $linesData = $this->getDataSource1($modeParams);
        [$dataOutput, $tableColumns, $tableDataHeader] = $this->triggerDataFollowManagePivot($linesData, $modeParams);
        // $linesData = collect(array_slice($linesData->toArray(), 0, 10));
        // dd($dataOutput);
        if (PivotReport::isEmptyArray($dataOutput)) {
            $dataColumns = $this->getParamColumns($dataOutput, $modeType);
            $paramColumns = array_values($dataColumns['data_filters']);
            $colRender = $dataColumns['binding_row_fields'];
            $tableColumns = $this->makeTableColumns($colRender);
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
            ]);
        }

        // Pivot data before render 
        $paramColumns = $this->getParamColumns($dataOutput, $modeType);
        // dd($paramColumns);
        $dataSource = $this->paginateDataSource($dataOutput, $pageLimit);

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
