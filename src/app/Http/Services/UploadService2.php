<?php

namespace App\Http\Services;

use App\Helpers\Helper;
use App\Models\Attachment;
use App\Models\Field;
use App\Utils\Constant;
use App\Utils\Support\AttachmentName;
use App\Utils\Support\Json\Properties;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class UploadService2
{
    protected $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
    public function destroy($toBeDeletedIds)
    {
        try {
            foreach ($toBeDeletedIds as $id) {
                $attachment = Attachment::find($id);
                Storage::disk('s3')->delete($attachment->url_thumbnail);
                Storage::disk('s3')->delete($attachment->url_media);
                $attachment->delete();
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    private function getAllowedFileTypes($type)
    {
        switch ($type) {
            case 'only_images':
                $result = "mimes:png,jpeg,gif,jpg,svg,webp";
                break;
            case 'only_videos':
                $result = "mimes:mp4,mov";
                break;
            case 'only_media':
                $result = "mimes:mp4,png,jpeg,gif,jpg,svg,webp";
                break;
            case 'only_non_media':
                $result = "mimes:csv,pdf,zip";
                break;
            case 'all_supported':
                $result = "";
                break;
            default:
                break;
        }
        return $result;
    }
    private function countFileUploadByCondition($fieldName, $id)
    {
        $fieldId = Field::where('name', $fieldName)->first()->id;
        return Attachment::where('object_type', $this->model)
            ->where('object_id', $id)->where('category', $fieldId)->get()->count() ?? 0;
    }
    public function store($request, $object_type = null, $object_id = null)
    {
        $thumbnailW = 150;
        $thumbnailH = 150;
        $allowedExts = ['jpeg', 'png', 'jpg', 'gif', 'webp'];
        // $attachmentP = Properties::getAllOf('attachment');
        $path = env('MEDIA_ROOT_FOLDER', 'media') . "/" . date(Constant::FORMAT_YEAR_MONTH) . "/";
        try {
            $fields = Helper::getDataDbByName('fields', 'name', 'id');
            $filesUpload = $request->files;
            $attachmentRows = [];
            foreach ($filesUpload as $fieldName => $files) {
                $property = Properties::getFor('attachment', '_' . $fieldName);
                // $property = ($attachmentP['_' . $fieldName] ?? ['max_file_size' => 10, 'max_file_count' => 10, 'allowed_file_types' => 'only_images']);
                $nameValidate = $fieldName . '.toBeUploaded';
                $maxFileSize = ($property['max_file_size'] == "" ? 10 : $property['max_file_size']) * 1024;
                $maxFileCount = ($property['max_file_count'] == "" ? 10 : $property['max_file_count']);
                $fileUploadCount = $this->countFileUploadByCondition($fieldName, $request->input('id'));
                $fileUploadRemainingCount = $maxFileCount - $fileUploadCount;
                $allowedFileTypes = $property['allowed_file_types'];
                $allowedFileTypes = $this->getAllowedFileTypes($allowedFileTypes);
                $request->validate([
                    $nameValidate => 'array|max:' . $fileUploadRemainingCount,
                    $nameValidate . '.*' => 'file|' . $allowedFileTypes . '|max:' . $maxFileSize,
                ]);
                // dd($files);
                $files = $files['toBeUploaded'];
                foreach ($files as $file) {
                    if (!$file->getClientOriginalExtension()) {
                        Toastr::warning('File without extension cannot be uploaded!', 'Upload File Warning');
                    } else {
                        $fileName = AttachmentName::slugifyImageName($file, $attachmentRows);
                        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                        $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
                        $mimeType = $file->getMimeType();
                        $imagePath = $path . $fileName;

                        Storage::disk('s3')->put($imagePath, file_get_contents($file), 'public');
                        // Log::info($fileName);
                        //Only crunch if the attachment is a photo
                        if (in_array($fileExt, $allowedExts)) {
                            $thumbnailImage = Image::make($file);
                            $thumbnailImage->fit($thumbnailW, $thumbnailH);
                            $resource = $thumbnailImage->stream();
                            $thumbnailFileName = $fileNameWithoutExt . "-{$thumbnailW}x{$thumbnailH}." . $fileExt;
                            $thumbnailPath = $path . $thumbnailFileName;
                            Storage::disk('s3')->put($thumbnailPath, $resource->__toString(), 'public');
                        }
                        // dd($fields[$fieldName);
                        $item = [
                            'url_thumbnail' => isset($thumbnailPath) ? $thumbnailPath : "",
                            'url_media' => $imagePath,
                            'url_folder' => $path,
                            'filename' => basename($imagePath),
                            'extension' => $fileExt,
                            'category' => $fields[$fieldName],
                            'owner_id' =>  (int)Auth::user()->id,
                            'mime_type' => $mimeType,
                        ];
                        if ($object_type) $item['object_type'] = $object_type;
                        if ($object_id) $item['object_id'] = $object_id;
                        array_push($attachmentRows, $item);
                    }
                }
            }

            // dd($attachmentRows);
            $invertedFields = array_flip($fields);
            $result = [];
            foreach ($attachmentRows as $row) {
                $insertedRow = Attachment::create($row);
                $result[$insertedRow['id']] = $invertedFields[$row['category']];
            }
            // dd($result);
            return $result;
        } catch (ValidationException $ve) {
            Toastr::warning($ve->getMessage() . "<br/>(You maybe upload more than the max allowed files.)", 'Upload File Problem');
        } catch (\Exception $e) {
            dd($e);
            // Toastr::warning($e, 'Upload File Warning');
        }
    }
}
