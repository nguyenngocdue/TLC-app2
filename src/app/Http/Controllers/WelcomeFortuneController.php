<?php

namespace App\Http\Controllers;

use App\BigThink\Math;
use App\Models\Diginet_business_trip_line;
use App\Models\Erp_vendor_external;
use App\Utils\Support\Erp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WelcomeFortuneController extends Controller
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
        $all = Diginet_business_trip_line::all();

        foreach ($all as $item) {
            $item->finger_print = Math::createDiginetFingerprint([$item['employeeid'], $item['tb_date']]);
            $item->save();
        }

        echo Math::createDiginetFingerprint(['TLCM00024', 20220101]);

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

        // return view("welcome-fortune", [
        //     'columns' => $columns,
        //     'dataSource' => $tables,
        // ]);
    }
}
