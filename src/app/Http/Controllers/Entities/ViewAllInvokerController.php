<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityExportCSV;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityShowQRList6;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFormula;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Utils\Support\Json\Definitions;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PDO;

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
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array_values(array_map(fn ($item) => $item['label'], $columns));
        $callback = function () use ($rows, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $array = [];
            foreach ($rows as $row) {
                foreach ($columns as $key => $column) {
                    $array[$column] = $row[$key];
                }
                fputcsv($file, $array);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
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
