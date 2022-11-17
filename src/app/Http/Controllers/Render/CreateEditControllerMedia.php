<?php

namespace App\Http\Controllers\Render;

use App\Helpers\Helper;
use App\Models\Media;
use App\Utils\Constant;
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

    private function setMediaParent($data, $uploadedIdColumnNames, $colNamehasAttachment = [])
    {
        if (!is_null($data) && (!is_null($uploadedIdColumnNames)) && count($uploadedIdColumnNames) > 0) {
            foreach (array_keys($uploadedIdColumnNames) as $key) {
                if (!is_null(Media::find($key))) {
                    $data->media()->save(Media::find($key));
                    // Update filed

                }
            }
            session([Constant::ORPHAN_MEDIA => []]);
        }
        session([Constant::ORPHAN_MEDIA => []]);
    }

    private function deleteMediaIfNeeded($dataInput)
    {
        $cateAttachment = DB::table('media_categories')->select('id', 'name')->get();
        $keyMediaDel = [];
        foreach ($cateAttachment as $value) {
            if (isset($dataInput[$value->name . "_deleted"])) {
                $idsDelete = explode(',', $dataInput[$value->name . "_deleted"]);
                foreach ($idsDelete as $value) {
                    $media = Media::find($value);
                    Storage::disk('s3')->delete($media->getAttributes()['url_thumbnail']);
                    Storage::disk('s3')->delete($media->getAttributes()['url_media']);
                    is_null($media) ? "" : $media->delete();
                    $keyMediaDel[] = $value;
                }
            }
        }
        return $keyMediaDel;
    }

    private function saveMediaValidator($action, $request, $dataInput, $data = [], $idsMediaDeleted = [], $colNamehasAttachment = [])
    {
        foreach (array_keys($dataInput) as $key) {
            if (str_contains($key, '_deleted')) {
                $uploadedIdColumnName0 = session(Constant::ORPHAN_MEDIA) ?? [];

                $uploadedIdColumnName1 = $this->handleUpload($request);
                if (count($idsMediaDeleted) > 0) {
                    $_colNameMediaUploaded = $uploadedIdColumnName1 + $uploadedIdColumnName0; // save old value of media were uploaded
                    $newColNameMediaUploaed = Helper::removeItemsByKeysArray($_colNameMediaUploaded, $idsMediaDeleted);
                    session([Constant::ORPHAN_MEDIA => $newColNameMediaUploaed]);
                    return true;
                }
                $oldSession = session(Constant::ORPHAN_MEDIA) ?? [];
                session([Constant::ORPHAN_MEDIA => $oldSession + $uploadedIdColumnName1]);
                if ($action === 'update') {
                    $this->setMediaParent($data, session(Constant::ORPHAN_MEDIA), $colNamehasAttachment);
                }
                return true;
            }
        }
        return false;
    }
}
