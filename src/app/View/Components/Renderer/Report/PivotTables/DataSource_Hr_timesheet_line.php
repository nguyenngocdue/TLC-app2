<?php

namespace App\View\Components\Renderer\Report\PivotTables;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\Reports\Hr_timesheet_line_100;
use App\Http\Controllers\Reports\TraitModeParamsReport;
use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\Http\Controllers\UpdateUserSettings;
use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\CurrentPathInfo;
use App\Utils\Support\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DataSource_Hr_timesheet_line extends Controller
{
    use TraitModeParamsReport;
    use TraitLibPivotTableDataFields;
    protected $modeType = '';
    protected $mode = '010';
    public function getType()
    {
        return "dashboard";
    }


    public function getDataSource1()
    {
        $primaryData = (new Hr_timesheet_line_100())->getDataSource([]);
        return $primaryData;
    }

    protected function getParamColumns($dataSource, $modeType)
    {
        [,,,,,,,,,,,,$dataFilters] = $this->getDataFields($dataSource, $modeType);
        $paramColumns = array_values($dataFilters);
        return $paramColumns;
    }


    protected function forwardToMode($request, $modeParams)
    {
        $input = $request->input();
        $isFormType = isset($input['form_type']);
        // dd($input);
        if ($isFormType && $input['form_type'] === 'updateParamsReport' || $isFormType && $input['form_type'] === 'updatePerPageReport') {
            (new UpdateUserSettings())($request);
        }
        return redirect($request->getPathInfo());
    }


    public function index(Request $request)
    {
        $input = $request->input();

        $routeName = $request->route()->action['as'];
        $entity = CurrentPathInfo::getEntityReport($request);
        $typeReport = CurrentPathInfo::getTypeReport($request);

        $modeParams = [];
        if (!$request->input('page') && !empty($input)) {
            return $this->forwardToMode($request, $modeParams);
        }
        $modeParams = $this->getModeParams($request);
        $dataSource = collect(array_slice($this->getDataSource1()->toArray(), 0, 1000));
        $paramColumns = $this->getParamColumns($dataSource, $this->modeType);

        [,,,,,,,,,,,,$dataFilters] = $this->getDataFields($dataSource);

        // dd($dataFilters);
        // dump($dataSource);
        return view("reports.report-pivot", [
            'modeType' => $this->modeType,
            'dataSource' => $dataSource,
            'modeParams' => $modeParams,
            'currentMode' => $this->mode,
            'paramColumns' => $paramColumns,
            'routeName' => $routeName,
            'typeReport' => $typeReport,
            'entity' => $entity,
        ]);
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
        $columnKeys = array_combine($columnKeys, $columnKeys);
        $callback = function () use ($rows, $columnKeys, $columnNames) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columnNames);
            $array = [];
            foreach ($rows as $row) {
                foreach ($columnKeys as $key => $column) {
                    $array[$column] = $row[$key];
                }
                fputcsv($file, $array);
            }
            fclose($file);
            // Log::info($array);
        };
        return response()->stream($callback, 200, $headers);
    }
}
