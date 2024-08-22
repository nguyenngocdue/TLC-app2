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
        $tables = DB::connection('sqlsrv')->select('SELECT * FROM [TLC_PROD].[dbo].[$ndo$migrations]');
        dump($tables);
        return view("welcome-fortune", []);
    }
}
