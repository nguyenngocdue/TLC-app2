<?php

namespace App\Http\Controllers\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitGetOptionPrint;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\Workflow\LibReports;
use App\Utils\ClassList;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateReport;
use App\Utils\Support\DocumentReport;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class Report_Parent2Controller extends Controller
{
    use TraitMenuTitle;
    use TraitParamsSettingReport;
    use TraitFunctionsReport;
    use TraitLibPivotTableDataFields2;
    use TraitUpdateBasicInfoDataSource;
    use ValidationAdvancedFilterReport;
    use TraitCreateSQL;
    use TraitGetOptionPrint;
    use TraitSettingLayout;
    use TraitGenerateValuesFromParamsReport;
    use TraitForwardModeReport;

    protected $mode = '010';
    protected $maxH = null;
    protected $tableTrueWidth = false;
    protected $pageLimit = 10;
    protected $typeView = '';
    protected $modeType = '';
    protected $rotate45Width = false;
    protected $viewName = '';
    protected $type = '';
    protected $optionPrint = 'landscape';
    protected $overTableTrueWidth = false;

    public function getType()
    {
        return $this->getTable();
    }

    public function getDataSource($modeParams)
    {
        $sql = $this->getSql($modeParams);
        if (is_null($sql) || !$sql) return collect();
        $sqlData = DB::select(DB::raw($sql));
        $collection = collect($sqlData);
        return $collection;
    }    
    protected function getDefaultValueParams($params, $request)
    {
        $x = 'picker_date';
        $params[$x] = DateReport::defaultPickerDate();
        return $params;
    }

    protected function getTable()
    {
        $tableName = CurrentRoute::getCurrentController();
        $tableName = substr($tableName, 0, strrpos($tableName, "_"));
        $tableName = strtolower(Str::plural($tableName));
        return $tableName;
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

    protected function forwardToMode($request, $params)
    {
        $input = $request->input();
        $isFormType = isset($input['form_type']);
        if ($isFormType && $input['form_type'] === 'updateParamsReport' || $isFormType && $input['form_type'] === 'updatePerPageReport') {
            (new UpdateUserSettings())($request);
        }
        return redirect($request->getPathInfo());
    }

    protected function tableDataHeader($data, $params)
    {
        return [];
    }

    private function makeModeTitleReport($routeName)
    {
        $lib = LibReports::getAll();
        $title = $lib[$routeName]['title'] ?? 'Empty Title 2';
        return $title;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        if (!$modeType) return [[]];
        $filters = $this->getDataFields($modeType)['filters'];
        $colParams = [];
        foreach ($filters as $key => $values) {
            $dataIndex = trim($key);
            $multiple = false;
            if (isset($values->multiple)) {
                if ($values->multiple == 'true' || $values->multiple) $multiple = true;
            }
            $a = [];
            if ($dataIndex === 'picker_date') {
                $a = ['renderer' => 'picker_date'];
            }
            $colParams[] = [
                'title' => $values->title ?? ucwords(str_replace('_', ' ', $key)),
                'allowClear' => $values->allowClear ?? false,
                'multiple' => $multiple,
                'dataIndex' => trim($dataIndex),
                'hasListenTo' => $values->hasListenTo ?? false,
            ] + $a;
        }
        // dd($colParams);
        return $colParams;
    }

    protected function getBasicInfoData($params)
    {
        return [[]];
    }

    protected function getTableColumns($dataSource, $params)
    {
        return [];
    }

    public function selectMonth($params)
    {
        $month = DocumentReport::getCurrentMonthYear();
        $projectId = 5;
        if (isset($params['month'])) {
            $month = $params['month'];
        }
        if (isset($params['project_id'])) {
            $projectId = $params['project_id'];
        }
        return [$month, $projectId];
    }

    private function isEmptyAllDataSource($dataSource)
    {
        // dd($dataSource);
        $isCheck = true;
        $data = $dataSource;
        if ($dataSource instanceof Collection) $data = $dataSource->toArray();
        foreach (array_values($data) as $values) {
            if (!empty($values)) {
                $isCheck = false;
                return $isCheck;
            }
        };
        return $isCheck;
    }

    private function filterEmptyItems($dataSource, $info)
    {
        return [];
        $emptyItems = [];
        foreach ($dataSource as $key => $value) {
            if (empty($value->toArray())) {
                $emptyItems[$key] = $info[$key]['date'];
            }
        }
        return $emptyItems;
    }
    public function changeDataSource($dataSource, $params)
    {
        // $dataSource = $this->addTooltip($dataSource);
        return $dataSource;
    }
    public function createInfoToRenderTable($dataSource)
    {
        return [];
    }

    public function getDisplayValueColumns()
    {
        return [];
    }

    public function index(Request $request)
    {
        
        $input = $request->input();
        // dd($input);
        // Check Validations
        if ($input) {
            $validation = $this->checkValidationAdvancedFilter($request);
            $failedVal = $validation ? $validation->failed() : [];
            unset($failedVal['picker_date']);
            if ($failedVal) {
                $messages = $validation->getMessageBag()->toArray();
                return back()
                    ->withErrors($messages)
                    ->withInput();
            }
        }
        $typeReport = CurrentPathInfo::getTypeReport2($request);
        $routeName = $request->route()->action['as'];
        $entity = CurrentPathInfo::getEntityReport($request);
        $params = $this->getParams($request);
        // dump($params);

        if (!$request->input('page') && !empty($input)) {
            return $this->forwardToMode($request, $params);
        }
        $pageLimit = $this->getPageParam($typeReport, $entity);

        $viewName =  CurrentPathInfo::getViewName($request);
        if ($this->viewName) $viewName = $this->viewName;

        $dataSource = $this->getDataSource($params);
        $dataSource = $this->changeDataSource($dataSource, $params);
        $dataSource  = $this->addTooltip($dataSource);

        $isEmptyAllDataSource = $this->isEmptyAllDataSource($dataSource);

        // dd($dataSource);
        $tableColumns = $this->getTableColumns($params, $dataSource);

        $tableColumns = $this->typeView === 'report-pivot' && empty($tableColumns) ? [] : $tableColumns;

        $tableDataHeader = $this->tableDataHeader($dataSource, $params);
        echo $this->getJS();
        $titleReport = $this->makeModeTitleReport($routeName);
        $modeType = $this->modeType;
        $paramColumns = $this->getParamColumns($dataSource, $modeType);
        //data to render for document reports
        [$dataRenderDocReport, $basicInfoData] = [[], []];
        if (str_contains($routeName, 'document-')) {
            $basicInfoData =  $this->getBasicInfoData($params);
            $dataRenderDocReport = [
                'basicInfoData' => $basicInfoData,
            ];
        }

        $emptyItems = $this->filterEmptyItems($dataSource, $basicInfoData);
        $settingComplexTable  = $this->createInfoToRenderTable($dataSource);
        $optionPrint = $params['optionPrintLayout'] ?? $this->optionPrint;
        $tableTrueWidth = $this->overTableTrueWidth && $optionPrint === 'landscape' ? 0 : $this->tableTrueWidth;
        // dump($dataSource);
        return view('reports.' . $viewName, [
            'entity' => $entity,
            'maxH' => $this->maxH,
            'mode' => $this->mode,
            'pageLimit' => $pageLimit,
            'routeName' => $routeName,
            'titleReport' => $titleReport,
            'params' => $params,
            'typeReport' => $typeReport,
            'modeType' => $this->modeType,
            'currentMode' =>  $this->mode,
            'typeOfView' => $this->typeView,
            'tableColumns' => $tableColumns,
            'paramColumns' => $paramColumns,
            'tableDataSource' => $dataSource,
            'tableDataHeader' => $tableDataHeader,
            'tableTrueWidth' => $tableTrueWidth,
            'rotate45Width' => $this->rotate45Width,
            'emptyItems' => $emptyItems,
            'isEmptyAllDataSource' => $isEmptyAllDataSource,
            'topTitle' => $this->getMenuTitle(),
            'settingComplexTable' => $settingComplexTable,
            'classListOptionPrint' => ClassList::DROPDOWN,
            'optionPrint' => $optionPrint,
            'layout' => $this->layout($optionPrint),
            'childrenMode' => $params['children_mode'] ?? 'not_children',
        ] + $dataRenderDocReport);
    }

    private function triggerDataFollowManagePivot($linesData, $modeType, $params)
    {
        $fn = (new DataPivotTable2);
        $data = $fn->makeDataPivotTable($linesData, $modeType, $params);
        return $data;
    }

    public function exportCSV(Request $request)
    {

        $entity = CurrentPathInfo::getEntityReport($request, '_ep');
        $params = $this->getParams($request, '_ep');
        
        $linesData = $this->getDataSource($params);
        $modeType = $this->modeType;
        // Pivot data before render 
        if ($modeType) {
            [$dataSource, $tableColumns,] = $this->triggerDataFollowManagePivot($linesData, $modeType, $params);
            [$columnKeys, $columnNames] = $this->makeColumnsPivotTable($dataSource, $params, $tableColumns);
        } else {
            //no pivot table
            $dataSource = $linesData;
            [$columnKeys, $columnNames] = $this->makeColumns($linesData, $params);
        }

        $rows = $this->makeRowsFollowColumns($dataSource, $columnKeys);
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

    protected function getJS()
    {
        return "";
    }
}
