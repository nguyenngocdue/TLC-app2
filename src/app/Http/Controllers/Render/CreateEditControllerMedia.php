<?php

namespace App\Http\Controllers\Render;

use App\Helpers\Helper;
use App\Models\Attachment;
use App\Models\Comment;
use App\Utils\Constant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait CreateEditControllerMedia
{
    private function handleUpload($request)
    {
        if (count($request->files) > 0) {
            $uploadedIdColumnNames = $this->uploadService->store($request);
            if (!is_array($uploadedIdColumnNames)) {
                $title = "Not find item";
                $message = $uploadedIdColumnNames->getMessage();
                $type = "warning";
                return view('components.feedback.result')->with(compact('message', 'title', 'type'));
            }
            return $uploadedIdColumnNames;
        }
        return session(Constant::ORPHAN_MEDIA) ?? [];
    }

    private function setMediaParent($data, $colNamesHaveAttachment)
    {
        $owner_id =  (int)Auth::user()->id;
        $uploadedIdColumnNames = json_decode(DB::table('attachments')->where([['owner_id', '=', $owner_id], ['object_id', '=', null], ['object_type', '=', null]])->select('id', 'category')->get(), true);
        $ids_idCates_media =  array_column($uploadedIdColumnNames, 'category', 'id');

        $media_cateTb = json_decode(DB::table('attachment_categories')->select('id', 'name')->get(), true);
        $ids_names_mediaCateTb = array_column($media_cateTb, 'name', 'id');

        if (!is_null($data) && (!is_null($uploadedIdColumnNames)) && count($uploadedIdColumnNames) > 0) {
            foreach ($ids_idCates_media as $key => $value) {
                if (in_array($ids_names_mediaCateTb[$value], $colNamesHaveAttachment)) {
                    if (!is_null($db = Attachment::find($key))) {
                        $data->media()->save($db);
                    }
                }
            }
        }
    }

    private function deleteMediaIfNeeded($dataInput)
    {
        $cateAttachment = DB::table('attachment_categories')->select('id', 'name')->get();
        $keyMediaDel = [];
        foreach ($cateAttachment as $value) {
            if (isset($dataInput[$value->name . "_deleted"])) {
                $idsDelete = explode(',', $dataInput[$value->name . "_deleted"]);
                foreach ($idsDelete as $value) {
                    $media = Attachment::find($value);
                    Storage::disk('s3')->delete($media->getAttributes()['url_thumbnail']);
                    Storage::disk('s3')->delete($media->getAttributes()['url_media']);
                    is_null($media) ? "" : $media->delete();
                    $keyMediaDel[] = $value;
                }
            }
        }
        return $keyMediaDel;
    }

    private function saveMedia($action, $request, $dataInput, $data = [], $colNamesHaveAttachment)
    {
        foreach (array_keys($dataInput) as $key) {
            if (str_contains($key, '_deleted')) {
                $this->handleUpload($request);
                if ($action === 'update') {
                    $this->setMediaParent($data, $colNamesHaveAttachment);
                }
                return true;
            }
        }
        return false;
    }
}
