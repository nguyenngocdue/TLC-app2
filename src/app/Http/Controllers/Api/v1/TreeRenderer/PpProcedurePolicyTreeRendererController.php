<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Models\Pp_doc;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpProcedurePolicyTreeRendererController extends _TreeRendererController
{
    function render(Request $request)
    {
        $ppId = $request->input('treeBodyObjectId');
        $pp = Pp_doc::find($ppId);
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

            'editPPRoute' => route("pp_docs.edit", $ppId),
            'updatePPRoute' => route("pp_docs.updateShortSingle"),

            'uploadFilePPRoute' => route("pp_docs.uploadFileShortSingle"),
            'deleteFilePPRoute' => route("pp_docs.deleteFileShortSingle"),
        ]);
    }
}
