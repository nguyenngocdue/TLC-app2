<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Console\Commands\Traits\CloneRunTrait;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_chklst_run_line;
use App\Models\Qaqc_insp_chklst_run;
use App\Models\Qaqc_insp_chklst_sht;
use App\Utils\Support\AttachmentName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SubmitFormAndUploadFileController extends Controller
{
    use CloneRunTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function submitAndUploadFile(Request $request)
    {
        try {
            $transactionId = $request->transactionId;
            $ownerId = $request->ownerId;
            $progress = $request->progress;
            $status = $request->status;
            $sheetId = $request->sheetId;
            $name = $request->name;
            $description = $request->description;
            $controlGroupId = $request->controlGroupId;
            $controlTypeId = $request->controlTypeId;
            $controlValueId = $request->controlValueId;
            $groupId = $request->groupId;
            $value = $request->value;
            $valueComment = $request->valueComment;
            if (in_array($controlValueId, ['4', '8'])) {
                $valueOnHold = $request->valueOnHold;
            } else {
                $valueOnHold = null;
            }
            if (!(cache('transactionId') === $transactionId)) {
                cache(['transactionId' => $transactionId], 10);
                $qaqcInspChklstRun = Qaqc_insp_chklst_run::create([
                    'owner_id' => $ownerId,
                    'qaqc_insp_chklst_sht_id' => $sheetId,
                    'progress' => $progress,
                    'status' => $status,
                ]);
                cache(['qaqcInspChklstRunId' => $qaqcInspChklstRun->id], 10);
                $qaqcInspChklstLine = Qaqc_insp_chklst_run_line::create([
                    'name' => $name,
                    'description' => $description,
                    'control_type_id' => $controlTypeId,
                    'value' => $value,
                    'value_comment' => $valueComment,
                    'value_on_hold' => $valueOnHold,
                    'qaqc_insp_group_id' => $groupId,
                    'qaqc_insp_chklst_run_id' => $qaqcInspChklstRun->id,
                    'qaqc_insp_control_value_id' => $controlValueId,
                    'qaqc_insp_control_group_id' => $controlGroupId,
                    'owner_id' => $ownerId,
                ]);
                $this->uploadFile($request, $ownerId, $qaqcInspChklstLine->id);
            } else {
                $qaqcRunId = cache('qaqcInspChklstRunId');
                $qaqcInspChklstLine = Qaqc_insp_chklst_run_line::create([
                    'name' => $name,
                    'description' => $description,
                    'control_type_id' => $controlTypeId,
                    'value' => $value,
                    'value_comment' => $valueComment,
                    'value_on_hold' => $valueOnHold,
                    'qaqc_insp_group_id' => $groupId,
                    'qaqc_insp_chklst_run_id' => $qaqcRunId,
                    'qaqc_insp_control_value_id' => $controlValueId,
                    'qaqc_insp_control_group_id' => $controlGroupId,
                    'owner_id' => $ownerId,
                ]);
                $this->uploadFile($request, $ownerId, $qaqcInspChklstLine->id);
            }

            return response()->json('Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json($th);
        }
    }
    public function submit2AndUploadFile(Request $request)
    {
        try {
            $lineId = $request->id;
            $ownerId = $request->ownerId;
            $sheetId = $request->sheetId;
            $progress = $request->progress;
            $status = $request->status;
            $controlValueId = $request->controlValueId;
            $value = $request->value;
            if (in_array($controlValueId, ['4', '8'])) {
                $valueOnHold = $request->valueOnHold;
            } else {
                $valueOnHold = null;
            }
            Qaqc_insp_chklst_sht::findOrFail($sheetId)->update([
                'progress' => $progress ?? null,
                'status' => $status ?? null,
            ]);
            Qaqc_insp_chklst_line::findOrFail($lineId)
                ->update([
                    'value' => $value ?? null,
                    'value_on_hold' => $valueOnHold ?? null,
                    'qaqc_insp_control_value_id' => $controlValueId ?? null,
                    'inspector_id' => $value != null ? $ownerId : null,
                ]);
            $this->uploadFile($request, $ownerId, $lineId);
            return response()->json('Successfully');
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json($th);
        }
    }
    private function uploadFile($request, $ownerId, $modelId, $modelName = 'App\\Models\\Qaqc_insp_chklst_line')
    {
        if (sizeof($request->files) > 0) {
            try {
                $nameIdFields = Helper::getDataDbByName('fields', 'name', 'id');
                $filesUpload = $request->files;
                $attachments = [];
                foreach ($filesUpload as $key => $files) {
                    foreach ($files as $file) {
                        $fileName = AttachmentName::slugifyImageName($file, $attachments);
                        $imageFileType = pathinfo($fileName, PATHINFO_EXTENSION);
                        $dt = Carbon::now('Asia/Ho_Chi_Minh');
                        $path = env('MEDIA_ROOT_FOLDER', 'media') . '/' . $dt->format('Y') . '/' . $dt->format('m') . '/';
                        $pathImage = $path . $fileName;
                        Storage::disk('s3')->put($pathImage, file_get_contents($file), 'public');
                        $pathThumbnail = $this->createThumbnailGiveImage($imageFileType, $file, $fileName, $path);
                        array_push($attachments, [
                            'url_thumbnail' => isset($pathThumbnail) ? $pathThumbnail : "",
                            'url_media' => $pathImage,
                            'url_folder' => $path,
                            'filename' => basename($pathImage),
                            'extension' => $imageFileType,
                            'mime_type' => $file->getMimeType(),
                            'category' => $nameIdFields[$key],
                            'owner_id' =>  $ownerId,
                            'object_id' => (int)$modelId,
                            'object_type' => $modelName,
                        ]);
                    }
                    $this->createAttachment($attachments);
                }
            } catch (\Throwable $th) {
            }
        }
    }
    private function createThumbnailGiveImage($imageFileType, $file, $fileName, $path, $imageFileTypeFrame = ['jpeg', 'png', 'jpg', 'gif', 'svg'])
    {
        $fileNameNormal = pathinfo($fileName, PATHINFO_FILENAME);
        if (in_array($imageFileType, $imageFileTypeFrame)) {
            $thumbnailImage = Image::make($file);
            $thumbnailImage->fit(150, 150);
            $resource = $thumbnailImage->stream();
            $fileNameThumbnail = $fileNameNormal . '-150x150.' . $imageFileType;
            $pathThumbnail = $path . $fileNameThumbnail;
            Storage::disk('s3')->put($pathThumbnail, $resource->__toString(), 'public');
        }
        return $pathThumbnail;
    }
    /**
     * createAttachment
     *
     * @param  mixed $attachments 
     * @return void
     */
    private function createAttachment($attachments)
    {
        foreach ($attachments as $attachment) {
            Attachment::create($attachment);
        }
    }
}
