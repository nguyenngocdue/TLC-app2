<?php

namespace App\Http\Controllers\Render;

use App\Helpers\Helper;
use App\Models\Media;
use App\Utils\Constant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

trait CreateEditControllerMedia
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
        return session(Constant::ORPHAN_MEDIA);
    }

    private function setMediaParent($data, $uploadedIdColumnNames)
    {
        if (!is_null($data) && (!is_null($uploadedIdColumnNames)) && count($uploadedIdColumnNames) > 0) {
            foreach (array_keys($uploadedIdColumnNames) as $key) {
                $data->media()->save(Media::find($key));
            }
            session([Constant::ORPHAN_MEDIA => []]);
        }
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



    private function saveMediaValidator($action, $request, $dataInput, $data = [], $idsMediaDeleted = [])
    {
        $hasAttachment = str_contains(array_keys($dataInput)[2], '_deleted');
        if (!$hasAttachment) return false;
        $uploadedIdColumnName0 = session(Constant::ORPHAN_MEDIA) ?? [];
        switch ($action) {
            case 'store':
                $uploadedIdColumnName1 = $this->handleUpload($request);
                if (count($idsMediaDeleted) > 0) {
                    $_colNameMediaUploaded = $uploadedIdColumnName1 + $uploadedIdColumnName0; // save old value of media were uploaded
                    Helper::removeItemsByKeysArray($_colNameMediaUploaded, $idsMediaDeleted);

                    session([Constant::ORPHAN_MEDIA => $_colNameMediaUploaded]);
                }

                $newSession = $uploadedIdColumnName1 + session(Constant::ORPHAN_MEDIA);

                session([Constant::ORPHAN_MEDIA => $newSession]);
                break;

            case 'update':
                $uploadedIdColumnName1 = $this->handleUpload($request);
                if (count($idsMediaDeleted) > 0) {
                    $_colNameMediaUploaded = $uploadedIdColumnName1 + $uploadedIdColumnName0; // save old value of media were uploaded
                    Helper::removeItemsByKeysArray($_colNameMediaUploaded, $idsMediaDeleted);

                    session([Constant::ORPHAN_MEDIA => $_colNameMediaUploaded]);
                }

                $newSession = $uploadedIdColumnName1 + session(Constant::ORPHAN_MEDIA);


                $this->setMediaParent($data, $newSession);
                break;

            default:
                break;
        }
        return true;
    }
}
