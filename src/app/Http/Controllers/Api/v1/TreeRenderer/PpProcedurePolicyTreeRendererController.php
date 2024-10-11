<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Models\Pp_procedure_policy;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpProcedurePolicyTreeRendererController extends _TreeRendererController
{
    private function getVersions()
    {
        return   [
            [
                "fileName" => "file version 01.pdf",
                'avatar' => '/images/avatar.jpg',
                'uploaded_by' => "user 1",
                'uploaded_at' => '01/02/2023',
            ],
            [
                "fileName" => "file version 02.pdf",
                'avatar' => '/images/avatar.jpg',
                'uploaded_by' => "user 1",
                'uploaded_at' => '01/02/2023',
            ],
            [
                "fileName" => "file version 03.pdf",
                'avatar' => '/images/avatar.jpg',
                'uploaded_by' => "user 1",
                'uploaded_at' => '01/02/2023',
            ],
        ];
    }

    private function getNotifyToId($ppId)
    {
        $procedure = Pp_procedure_policy::query()
            ->where('id', $ppId)
            ->first();
        return $procedure->notify_to_id ?? 756;
    }

    function render(Request $request)
    {
        $ppId = $request->input('treeBodyObjectId');
        $notifyToId = $this->getNotifyToId($ppId);
        $versions = $this->getVersions();
        $notifyTo = Term::query()->where('field_id', 318)->get();

        return view('components.renderer.view-all-tree-explorer.pp-procedure-policy', [
            'ppId' => $ppId,
            'notifyToId' => $notifyToId,
            'notifyTo' => $notifyTo,
            'versions' => $versions,
            'updatePPRoute' => route("pp_procedure_policies.updateShortSingle"),
            'loadDynamicNotifyToTree' => route("pp_procedure_policy_notify_to_tree_explorer"),
        ]);
    }
}
