<?php

namespace App\Http\Controllers;

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
        $tables = Erp_vendor_external::query()->paginate(100);

        $columns = array_map(fn($c) => ['dataIndex' => $c], Erp::getAllColumns('erp_vendor'));

        $columns = [
            ['dataIndex' => 'No_'],
            ['dataIndex' => 'Name'],
            ['dataIndex' => 'VAT Registration No_'],
            ['dataIndex' => 'Address'],
            ['dataIndex' => 'Description'],
            ['dataIndex' => 'Search Description'],
        ];

        return view("welcome-fortune", [
            'columns' => $columns,
            'dataSource' => $tables,
        ]);
    }
}
