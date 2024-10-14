<?php

namespace App\Http\Controllers\Api\v1\TreeRenderer;

use App\Models\Pp_procedure_policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PpProcedurePolicyPublishedVersionRendererController //extends _TreeRendererController
{
    private function getPP($ppId)
    {
        return Pp_procedure_policy::query()
            ->where('id', $ppId)
            ->with([
                'attachment_procedure_policy' => function ($q) {
                    $q->with(["getUploader" => function ($q) {
                        $q->with("getAvatar");
                    }]);
                },
            ])
            ->first();
    }

    private function getVersions(Pp_procedure_policy $pp)
    {
        $attachments = $pp->attachment_procedure_policy;
        return $attachments
            ->map(function ($att) {
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
            })
            ->sortBy(fn($file) => $file['fileName'])
            ->values();
    }

    function renderToJson(Request $request)
    {
        $ppId = $request->input('ppId');
        $pp = $this->getPP($ppId);
        $versions = $this->getVersions($pp);

        $result = [
            'selectedVersionId' => $pp->version_id,
            'hits' => $versions,
        ];

        return $result;
    }
}
