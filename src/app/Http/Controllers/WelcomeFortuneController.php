<?php

namespace App\Http\Controllers;

use App\Http\Services\CleanOrphanAttachment\ListAttachmentService;
use App\Http\Services\CleanOrphanAttachment\ListFileService;
use App\Http\Services\CleanOrphanAttachment\ListFolderService;
use App\Models\Erp_item;
use App\Models\Erp_vendor;
use App\Models\Erp_vendor_external;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
