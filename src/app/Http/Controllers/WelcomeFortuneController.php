<?php

namespace App\Http\Controllers;

use App\Http\Services\CleanOrphanAttachment\ListAttachmentService;
use App\Http\Services\CleanOrphanAttachment\ListFileService;
use App\Http\Services\CleanOrphanAttachment\ListFolderService;
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
        $sql = 'SELECT /* TOP (1000)*/ * 
        FROM [TLC_PROD].[dbo].[TLC_LLC$Item$437dbf0e-84ff-417a-965d-ed2bb9650972]';
        $tables = DB::connection('sqlsrv')->select($sql);

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
