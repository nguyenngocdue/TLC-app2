<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Models\Pp_procedure_policy;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpProcedurePolicyTreeRendererController extends _TreeRendererController
{
    function render(Request $request)
    {
        $ppId = $request->input('treeBodyObjectId');
        $pp = Pp_procedure_policy::find($ppId);
        $notifyToId = $pp->notify_to ?? 756;
        $versionId = $pp->version_id;
        $notifyToOptionList = Term::query()->where('field_id', 318)->get();

        return view('components.renderer.view-all-tree-explorer.pp-procedure-policy', [
            'ppId' => $ppId,
            'notifyTo' => $notifyToOptionList,

            'notifyToId' => $notifyToId,
            'loadDynamicNotifyToTree' => route("pp_procedure_policy_notify_to_tree_explorer"),

            'versionId' => $versionId,
            'loadDynamicPublishedVersion' => route("pp_procedure_policy_published_version"),

            'uploadFilePPRoute' => route("pp_procedure_policies.uploadFileShortSingle"),
            'editPPRoute' => route("pp_procedure_policies.edit", $ppId),
            'updatePPRoute' => route("pp_procedure_policies.updateShortSingle"),
        ]);
    }
}
