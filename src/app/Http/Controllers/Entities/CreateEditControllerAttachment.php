<?php

namespace App\Http\Controllers\Entities;


use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait CreateEditControllerAttachment
{
    private function handleUpload($request)
    {
        if (count($request->files) > 0) {
            $uploadedIdColumnNames = $this->uploadService->store($request);
            // dd($uploadedIdColumnNames);
            if (!is_array($uploadedIdColumnNames)) {
                $title = "Not find item";
                $message = $uploadedIdColumnNames->getMessage();
                $type = "warning";
                return view('components.feedback.result')->with(compact('message', 'title', 'type'));
            }
            return $uploadedIdColumnNames;
        }
        return [];
    }

    private function setMediaParent($data, $colNamesHaveAttachment)
    {
        // dd($data);
        $owner_id =  (int)Auth::user()->id;
        $uploadedIdColumnNames = json_decode(DB::table('attachments')->where([['owner_id', '=', $owner_id], ['object_id', '=', null], ['object_type', '=', null]])->select('id', 'category')->get(), true);
        $ids_idCates_media =  array_column($uploadedIdColumnNames, 'category', 'id');

        $media_cateTb = json_decode(DB::table('fields')->select('id', 'name')->get(), true);
        $ids_names_mediaCateTb = array_column($media_cateTb, 'name', 'id');

        if (!is_null($data) && (!is_null($uploadedIdColumnNames)) && count($uploadedIdColumnNames) > 0) {
            foreach ($ids_idCates_media as $key => $value) {
                if (in_array($ids_names_mediaCateTb[$value], $colNamesHaveAttachment)) {
                    if (!is_null($db = Attachment::find($key))) {
                        $data->attachment()->save($db);
                    }
                }
            }
        }
    }


    private function deleteMediaIfNeeded($dataInput)
    {

        $cateAttachment = DB::table('fields')->select('id', 'name')->get();
        $keyMediaDel = [];
        foreach ($cateAttachment as $value) {
            // dd($dataInput, $cateAttachment);
            if (isset($dataInput["attachment_deleted_" . $value->name])) {
                // dd($dataInput, $value->name);
                $idsDelete = explode(',', $dataInput["attachment_deleted_" . $value->name]);
                // dd($value->name, $idsDelete);
                foreach ($idsDelete as $value) {
                    $attachments = Attachment::find($value);
                    // dd($attachments);
                    Storage::disk('s3')->delete($attachments->getAttributes()['url_thumbnail']);
                    Storage::disk('s3')->delete($attachments->getAttributes()['url_media']);
                    is_null($attachments) ? "" : $attachments->delete();
                    $keyMediaDel[] = $value;
                }
            }
        }
        return $keyMediaDel;
    }

    private function saveAndGetIdsMedia($request, $dataInput)
    {
        // dd($dataInput);
        foreach (array_keys($dataInput) as $key) {
            if (str_contains($key, 'attachment_deleted_')) {
                return $this->handleUpload($request);
            }
        }
        return [];
    }
}
