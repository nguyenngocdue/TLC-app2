<?php


namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Http\Services\CleanOrphanAttachment\ListAttachmentService;

class OrphanAttachmentController extends Controller
{
    function __construct(
        private ListAttachmentService $listAttachmentService,
    ) {
    }

    public function getType()
    {
        return "dashboard";
    }

    function index()
    {
        $this->listAttachmentService->handle();
    }
}
