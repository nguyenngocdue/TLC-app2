<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityExportCSV;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityShowQRList6;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityDynamicType;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;

class ViewAllInvokerController extends Controller
{
    use TraitEntityExportCSV;
    use TraitEntityShowQRList6;
    use TraitEntityDynamicType;
    use TraitViewAllFunctions;

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
}
