<?php

namespace App\Http\Controllers;

use App\Http\Services\CleanOrphanAttachment\ListAttachmentService;
use App\Http\Services\CleanOrphanAttachment\ListFileService;
use App\Http\Services\CleanOrphanAttachment\ListFolderService;
use App\Models\Erp_item;
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
        $tables = Erp_item::query()->paginate(100);

        $columns = [
            ['dataIndex' => 'No_'],
            ['dataIndex' => 'Description'],
            ['dataIndex' => 'Search Description'],
        ];
        return view("welcome-fortune", [
            'columns' => $columns,
            'dataSource' => $tables,
        ]);
    }
}
