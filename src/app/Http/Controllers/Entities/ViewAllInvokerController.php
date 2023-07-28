<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityExportCSV;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityShowQRList6;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Utils\Excel\Excel;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ViewAllInvokerController extends Controller
{
    use TraitEntityExportCSV;
    use TraitEntityShowQRList6;
    use TraitEntityDynamicType;
    use TraitViewAllFunctions;
    use TraitEntityFormula;

    protected $type = "";
    protected $typeModel = '';
    protected $permissionMiddleware;

    public function __construct()
    {
        $this->assignDynamicTypeViewAllQrList6();
        $this->middleware("permission:{$this->permissionMiddleware['read']}")->only('index');
    }

    public function getType()
    {
        return $this->type;
    }

    public function exportCSV()
    {
        [$columns, $dataSource] = $this->normalizeDataSourceAndColumnsFollowAdvanceFilter();
        $rows = [];
        foreach ($dataSource as $no => $dataLine) {
            $rows[] = $this->makeRowData($columns, $dataLine, $no + 1);
        }
        $fileName = $this->type . '.csv';
        $columns = array_values(array_map(fn ($item) => $item['label'], $columns));
        $headers = Excel::header($fileName);
        $callback = Excel::export($columns,$rows);
        return response()->stream($callback, 200, $headers);
    }
    private function groupByDataSource($request,$dataSource){
        if($request->groupBy){
            $result = [];
            foreach ($dataSource as $key => $item) {
                $groupKey = substr($item[$request->groupBy], 0, $request->groupByLength);
                if (!isset($result[$groupKey])) {
                    $result[$groupKey] = [];
                }
                $result[$groupKey][$key] = $item;
            }
            return $result;
        }
        return $dataSource;
    }
    private function makeDataSourceForViewMatrix($request,$dataSource){
        $rows = [];
        foreach ($dataSource as $key => $value) {
           $rows[] = [$key];
           foreach ($value as $no => $item) {
            if(isset($item[$request->groupBy])) unset($item[$request->groupBy]);
            $item = array_values(array_map(fn($item) => $item->value ?? $item,$item));
            array_unshift($item,($no + 1));
            $rows[] = $item;
           }
        }
        return $rows;
        
    }
    private function sortDataValueFollowColumns($columns,$dataSource){
        $columns = array_column($columns,'dataIndex');
        $result = [];
        foreach ($dataSource as $item) {
            $arrayTemp = [];
            foreach ($columns as $key) {
                $arrayTemp[$key] = $item[$key];
            }
            $result[] = $arrayTemp;
        }
        return $result;
    }
    public function exportCSV2(Request $request){
        if($modelPath = $request->modelPath){
            [,$columns,$dataSource] = (new ($modelPath))->getParams();
            // dump($columns);
            // dd($dataSource);
            $dataSource = $this->sortDataValueFollowColumns($columns,$dataSource);
            $dataSource = $this->groupByDataSource($request,$dataSource);
            $columns = array_filter($columns,fn($item) => !isset($item['hidden']));
            $columns = array_values(array_map(fn ($item) => (isset($item['title']) ? 
            Str::headline(strip_tags($item['title'])) 
            : Str::headline($item['dataIndex'])), $columns));
            array_unshift($columns,  'No.');
            $fileName = $this->type . '_matrix.csv';
            $headers = Excel::header($fileName);
            $dataSource = $this->makeDataSourceForViewMatrix($request,$dataSource);
            $callback = Excel::export($columns,$dataSource);
            return response()->stream($callback, 200, $headers);
        }
    }
    public function duplicateMultiple(Request $request)
    {
        try {
            $strIds = $request->ids;
            $ids = explode(',', $strIds) ?? [];
            $arrFail = [];
            $arrDuplicate = [];
            foreach ($ids as $id) {
                $theLine = $this->typeModel::findOrFail($id)->toArray();
                if (!$theLine) {
                    $arrFail[] = $id;
                }
                $settingDuplicatable = $this->getSettingDuplicatable();
                try {
                    foreach ($settingDuplicatable as $key => $value) {
                        if (!$value) {
                            $theLine[substr($key, 1)] = null;
                        }
                    };
                    $theLine = $this->applyFormula($theLine, 'store');
                    $this->typeModel::create($theLine);
                    $arrDuplicate[] = $id;
                } catch (\Throwable $th) {
                    $arrFail[] = $id;
                }
            }
            $arrFail = array_unique($arrFail) ?? [];
            return ResponseObject::responseSuccess(
                $arrDuplicate,
                [$arrFail],
                "Duplicate document successfully!",
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getPrevious()->getMessage(),
            );
        }

        return response()->json($strIds);
    }
    private function getSettingDuplicatable()
    {
        $props =  SuperProps::getFor($this->type)['props'];
        return array_map(fn ($item) => $item['duplicatable'], $props);
    }
}
