<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibCsv;
use App\Http\Services\CleanOrphanAttachment\ListAttachmentService;
use App\Http\Services\CleanOrphanAttachment\ListFileService;
use App\Http\Services\CleanOrphanAttachment\ListFolderService;
use App\Models\Erp_item;
use App\Models\Erp_vendor;
use App\Models\Erp_vendor_external;
use App\Utils\Support\Erp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WelcomeFortunePayslipController extends Controller
{
    function __construct(
        // private ListFolderService $listFolderService,
        // private ListFileService $listFileService,
        // private ListAttachmentService $listAttachmentService,
    )
    {
        // $listFolderService->handle();
        // $listFileService->handle();
        // $listAttachmentService->handle();
    }

    function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {

        $csv = LibCsv::getAll();
        $index = 0;
        foreach ($csv as $key => &$value) {
            $value['key'] = $key;
            $value['index'] = $index++;
            // $value['xxx'] = (100 + $value['group'] * 10 + $value['order']) . (100 + $value['index']);
            $value['xxx'] = ($value['group'] * 100 + $value['order']);
        }

        uasort($csv, fn($a, $b) => ($a['xxx'] <=> $b['xxx']));
        // dump($csv);

        $rows = DB::table('wp_parse_ps')->get();
        // dump($rows[0]);
        echo "user_id,datetime,";
        foreach ($csv as $key => $value) {
            echo $value['eng'];
            echo ",";
        }
        echo "<br/>\n";

        echo "Vietnamese,,";
        foreach ($csv as $key => $value) {
            echo $value['viet'];
            echo ",";
        }
        echo "<br/>\n";

        echo "key,,";
        foreach ($csv as $key => $value) {
            echo $value['key'];
            echo ",";
        }
        echo "<br/>\n";

        echo "formula,,";
        foreach ($csv as $key => $value) {
            echo $value['formular'];
            echo ",";
        }
        echo "<br/>\n";

        echo "unit,,";
        foreach ($csv as $key => $value) {
            echo $value['unit'];
            echo ",";
        }
        echo "<br/>\n";

        echo "group,,";
        foreach ($csv as $key => $value) {
            echo $value['group'];
            echo ",";
        }
        echo "<br/>\n";

        echo "order,,";
        foreach ($csv as $key => $value) {
            echo $value['order'];
            echo ",";
        }
        echo "<br/>\n";

        echo "hidden,,";
        foreach ($csv as $key => $value) {
            echo $value['hidden'];
            echo ",";
        }
        echo "<br/>\n";

        foreach ($rows as $row) {
            $values = json_decode($row->value);
            // dump($values->staff_name);
            // if (!str_contains($values->staff_name, ' CAO ')) continue;
            echo $row->user_id;
            echo ",";
            echo $row->datetime;
            // dump($csv);
            // dump($values);
            foreach ($csv as $key => $value) {
                echo ",";
                echo $values->$key ?? "";
            }
            // dump($row);
            echo "<br/>\n";
            // if ($count++ > 10) break;
        }

        // $tables = Erp_vendor_external::query()->paginate(100);

        // $columns = array_map(fn($c) => ['dataIndex' => $c], Erp::getAllColumns('erp_vendor'));

        // $columns = [
        //     ['dataIndex' => 'No_'],
        //     ['dataIndex' => 'Name'],
        //     ['dataIndex' => 'VAT Registration No_'],
        //     ['dataIndex' => 'Address'],
        //     ['dataIndex' => 'Description'],
        //     ['dataIndex' => 'Search Description'],
        // ];

        dd();
        return view("welcome-fortune", [
            // 'columns' => $columns,
            // 'dataSource' => $tables,
        ]);
    }
}
