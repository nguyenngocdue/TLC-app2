<?php

namespace App\Http\Controllers\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\Workflow\LibReports;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class Report_ParentController extends Controller
{
    use TraitMenuTitle;
    use TraitModeParamsReport;
    use TraitFunctionsReport;
    use TraitDataSourceReport;
    abstract protected function getSqlStr($modeParams);
    abstract protected function getTableColumns($modeParams, $dataSource);

    protected $rotate45Width = false;
    protected $groupBy = false;
    protected $mode = '010';
    protected $groupByLength = 7;
    protected $maxH = null;
    protected $tableTrueWidth = false;
    protected $pageLimit = 10;
    protected $typeView = '';
    protected $modeType = '';
    protected $viewName = '';

    protected function getTable()
    {
        $tableName = CurrentRoute::getCurrentController();
        $tableName = substr($tableName, 0, strrpos($tableName, "_"));
        $tableName = strtolower(Str::plural($tableName));
        return $tableName;
    }

    public function getType()
    {
        return $this->getTable();
    }

    protected function changeValueData($dataSource, $modeParams)
    {
        return $dataSource;
    }

    private function paginateDataSource($dataSource, $pageLimit)
    {
        $page = $_GET['page'] ?? 1;
        $dataSource = (new LengthAwarePaginator($dataSource->forPage($page, $pageLimit), $dataSource->count(), $pageLimit, $page))->appends(request()->query());
        return $dataSource;
    }

    protected function getPageParam($typeReport, $entity)
    {
        $settings = CurrentUser::getSettings();
        if (!isset($settings[$entity])) return $this->pageLimit;
        if (isset($settings[$entity][strtolower($typeReport)]['per_page'])) {
            $pageLimit = $settings[$entity][strtolower($typeReport)]['per_page'];
            return $pageLimit;
        }
        return $this->pageLimit;
    }

    protected function getDefaultValueModeParams($modeParams, $request)
    {
        return $modeParams;
    }

    protected function getColorLegends()
    {
        return [];
    }

    protected function forwardToMode($request, $modeParams)
    {
        $input = $request->input();
        $isFormType = isset($input['form_type']);
        if ($isFormType && $input['form_type'] === 'updateParamsReport' || $isFormType && $input['form_type'] === 'updatePerPageReport') {
            (new UpdateUserSettings())($request);
        }
        return redirect($request->getPathInfo());
    }

    protected function tableDataHeader($modeParams, $data)
    {
        return [];
    }

    public function makeTitleReport($routeName)
    {
        $lib = LibReports::getAll();
        $titleReport = $lib[$routeName]['title'] ?? '';
        // $topTitle = $lib[$routeName]['top_title'] ?? '';
        return $titleReport;
    }


    public function makeTitleForTables($modeParams)
    {
        return [];
    }

    public function index(Request $request)
    {
        $input = $request->input();
        $typeReport = CurrentPathInfo::getTypeReport($request);
        $routeName = $request->route()->action['as'];
        $entity = CurrentPathInfo::getEntityReport($request);
        $currentUserId = Auth::id();
        $modeParams = $this->getModeParams($request);
        $modeParams = $this->getDefaultValueModeParams($modeParams, $request);

        if (!$request->input('page') && !empty($input)) {
            return $this->forwardToMode($request, $modeParams);
        }
        $pageLimit = $this->getPageParam($typeReport, $entity);
        $titleReport = $this->makeTitleReport($routeName);
        $topTitle = $this->getMenuTitle();
        if (!str_contains($routeName, 'document-')) {
            $dataSource = $this->getDataSource($modeParams);
            $dataSource = $this->enrichDataSource($dataSource, $modeParams);
            $dataSource = $this->transformDataSource($dataSource, $modeParams);
            $dataSource = $this->changeValueData($dataSource, $modeParams);
            $dataSource = $this->paginateDataSource($dataSource, $pageLimit);

            $viewName =  CurrentPathInfo::getViewName($request);
            if ($this->viewName) $viewName = $this->viewName;
            $tableColumns = $this->getTableColumns($dataSource, $modeParams);
            $tableDataHeader = $this->tableDataHeader($modeParams, $dataSource);
            echo $this->getJS();

            return view('reports.' . $viewName, [
                'typeOfView' => $this->typeView,
                'modeType' => $this->modeType,
                'maxH' => $this->maxH,
                'entity' => $entity,
                'mode' => $this->mode,
                'pageLimit' => $pageLimit,
                'routeName' => $routeName,
                'titleReport' => $titleReport,
                'modeParams' => $modeParams,
                'typeReport' => $typeReport,
                'currentMode' =>  $this->mode,
                'tableColumns' => $tableColumns,
                'tableDataSource' => $dataSource,
                'currentUserId' => $currentUserId,
                'groupBy' => $this->groupBy,
                // 'modeOptions' => $this->$entity(),
                'tableDataHeader' => $tableDataHeader,
                'rotate45Width' => $this->rotate45Width,
                'groupByLength' => $this->groupByLength,
                'topTitle' => $topTitle,
                'paramColumns' => $this->getParamColumns(),
                'legendColors' => $this->getColorLegends(),
                'tableTrueWidth' => $this->tableTrueWidth,
            ]);
        }
        $basicInfoData =  $this->getBasicInfoData($modeParams);
        $dataSource = $this->getDataSource2($modeParams);

        // dd($tableColumns);
        return view('reports.'. $this->viewName, [
            'paramColumns' => $this->getParamColumns(),
            'entity' => $entity,
            'routeName' => $routeName,
            'titleReport' => $titleReport,
            'typeReport' => $typeReport,
            'topTitle' => $this->getMenuTitle(),
            'modeParams' => $modeParams,
            'currentMode' =>  $this->mode,
            'groupByLength' => $this->groupByLength,
            'groupBy' => $this->groupBy,

            'basicInfoData' => $basicInfoData,
            'tableDataSource' => $dataSource,
            'titleTables' => $this->makeTitleForTables($modeParams),
        ]);
    }
    protected function modifyDataToExportCSV($dataSource)
    {
        return $dataSource;
    }

    public function exportCSV(Request $request)
    {
        $entity = CurrentPathInfo::getEntityReport($request, '_ep');
        $modeParams = $this->getModeParams($request, '_ep');
        $dataSource = $this->getDataSource($modeParams);
        $dataSource = $this->enrichDataSource($dataSource, $modeParams);
        $dataSource = $this->transformDataSource($dataSource, $modeParams);
        $dataSource = $this->modifyDataToExportCSV($dataSource);
        // dd($modeParams, $dataSource);
        [$columnKeys, $columnNames] = $this->makeColumns($dataSource, $modeParams);
        $rows = $this->makeRowsFollowColumns($dataSource, $columnKeys);
        $fileName = $entity . '_' . date('d:m:Y H:i:s') . '.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        // $columnKeys = array_combine($columnKeys, $columnKeys);
        $callback = function () use ($rows, $columnKeys, $columnNames) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columnNames);
            $array = [];
            foreach ($rows as $row) {
                foreach ($columnKeys as $key) {
                    $array[$key] = $row[$key];
                }
                fputcsv($file, $array);
            }
            fclose($file);
            // Log::info($array);
        };
        return response()->stream($callback, 200, $headers);
    }

    protected function getJS()
    {
        return "";
    }
}
