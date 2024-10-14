<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Models\Pp_procedure_policy;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpProcedurePolicyTreeRendererController extends _TreeRendererController
{
    private function getVersions(Pp_procedure_policy $pp)
    {
        $attachments = $pp->attachment_procedure_policy;
        return $attachments->map(function ($att) {
            $avatar = $att->getUploader->getAvatar;
            $src = $avatar ? $avatar->url_thumbnail : '/images/avatar.jpg';
            $src = app()->pathMinio($src);
            return [
                "id" => $att->id,
                "fileName" => $att->filename,
                'avatar' => $src,
                "src" => app()->pathMinio($att->url_media),
                'uploaded_by' => $att->getUploader->first_name,
                'uploaded_at' => $att->created_at->format('d/m/Y'),
            ];
        });
    }

    function render(Request $request)
    {
        $ppId = $request->input('treeBodyObjectId');
        $pp = Pp_procedure_policy::query()
            ->where('id', $ppId)
            ->with([
                'attachment_procedure_policy' => function ($q) {
                    $q->with(["getUploader" => function ($q) {
                        $q->with("getAvatar");
                    }]);
                },
            ])
            ->first();
        $notifyToId = $pp->notify_to ?? 756;
        $versionId = $pp->version_id;
        $versions = $this->getVersions($pp);
        $notifyTo = Term::query()->where('field_id', 318)->get();

        return view('components.renderer.view-all-tree-explorer.pp-procedure-policy', [
            'ppId' => $ppId,

            'notifyToId' => $notifyToId,
            'notifyTo' => $notifyTo,

            'versionId' => $versionId,
            'versions' => $versions,

            'editPPRoute' => route("pp_procedure_policies.edit", $ppId),
            'updatePPRoute' => route("pp_procedure_policies.updateShortSingle"),
            'loadDynamicNotifyToTree' => route("pp_procedure_policy_notify_to_tree_explorer"),
        ]);
    }
}
