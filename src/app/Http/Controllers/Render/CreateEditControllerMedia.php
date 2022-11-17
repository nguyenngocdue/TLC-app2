<?php

namespace App\Http\Controllers\Render;

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



    private function saveMediaValidator($action, $request, $dataInput, $data = [], $props)
    {
        $hasAttachment = str_contains(array_keys($dataInput)[2], '_deleted');
        if (!$hasAttachment) return false;

        $itemValidations = [];
        foreach ($props as $value) {
            if (!is_null($value['validation'])) $itemValidations[$value['column_name']] = $value['validation'];
        }
        $validator = Validator::make($request->all(), $itemValidations);

        $uploadedIdColumnName0 = session(Constant::ORPHAN_MEDIA) ?? [];

        switch ($action) {
            case 'update':
                if ($validator->fails()) {
                    $keyMediaDel = $this->deleteMediaIfNeeded($dataInput);
                    $uploadedIdColumnName1 = $this->handleUpload($request);

                    $_colNameMediaUploaded = $uploadedIdColumnName0 + $uploadedIdColumnName1; // save old value of media were uploaded
                    foreach ($keyMediaDel as $value) {
                        unset($_colNameMediaUploaded[$value]);
                    }
                    session([Constant::ORPHAN_MEDIA => $_colNameMediaUploaded]);
                } else {
                    $uploadedIdColumnName1 = $this->handleUpload($request);
                    $this->setMediaParent($data, $uploadedIdColumnName1);
                }
                $this->setMediaParent($data, $uploadedIdColumnName0);
                break;
            case 'store':
                if ($validator->fails()) {
                    $keyMediaDel = $this->deleteMediaIfNeeded($dataInput);
                    $_colNameMediaUploaded = $this->handleUpload($request) + $uploadedIdColumnName0; // save old value of media were uploaded

                    foreach ($keyMediaDel as $value) {
                        unset($_colNameMediaUploaded[$value]);
                    }
                    session([Constant::ORPHAN_MEDIA => $_colNameMediaUploaded]);
                } else {
                    // validations were successful at the first time

                    $_colNameMediaUploaded = $this->handleUpload($request); // save old value of media were uploaded
                    session([Constant::ORPHAN_MEDIA => $_colNameMediaUploaded]);
                }
                break;

            default:
                break;
        }
        return true;
    }
}
