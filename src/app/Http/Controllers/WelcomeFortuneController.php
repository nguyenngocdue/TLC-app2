<?php

namespace App\Http\Controllers;

use App\BigThink\Math;
use App\Models\Diginet_business_trip_line;
use App\Models\Erp_vendor_external;
use App\Models\Rp_block;
use App\Models\Rp_report;
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
        $result = [];

        $allReports = Rp_report::query()
            ->with('getPages.getBlockDetails.getBlock')
            ->get();

        foreach ($allReports as $report) {
            $result[$report->id] = [
                'item' => $report,
                'children' => [],
            ];
            foreach ($report->getPages as $page) {
                $result[$report->id]['children'][$page->id]['item'] = $page;
                $result[$report->id]['children'][$page->id]['children'] = [];
                foreach ($page->getBlockDetails as $blockDetail) {
                    $block = $blockDetail->getBlock;
                    $result[$report->id]['children'][$page->id]['children'][$block->id]['item'] = $block;
                }
            }
        }


        dump(array_pop($result));

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
