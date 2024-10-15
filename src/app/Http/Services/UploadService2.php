<?php

namespace App\Http\Services;

use App\Helpers\Helper;
use App\Models\Attachment;
use App\Models\Field;
use App\Utils\Constant;
use App\Utils\Support\Json\Properties;
use App\View\Components\Renderer\Attachment2a;
use Illuminate\Support\Str;
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
    public function destroy($toBeDeletedIds, $isHardDelete = false)
    {
        try {
            foreach ($toBeDeletedIds as $id) {
                $attachment = Attachment::find($id);
                Storage::disk('s3')->delete($attachment->url_thumbnail);
                Storage::disk('s3')->delete($attachment->url_media);
                if ($isHardDelete) {
                    $attachment->forceDelete();
                } else {
                    $attachment->delete();
                }
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    private function getAllowedFileTypes($type)
    {
        $prefix = "mimes:";
        $result = Attachment2a::$TYPE[$type]['string'];
        return $prefix . $result;
    }
    private function countFileUploadByCondition($fieldName, $id)
    {
        $fieldId = Field::where('name', $fieldName)->first()->id;
        return Attachment::where('object_type', $this->model)
            ->where('object_id', $id)
            ->where('category', $fieldId)
            ->where('deleted_at', null)
            ->get()
            ->count() ?? 0;
    }
    public function store($request, $object_type = null, $object_id = null)
    {
        $thumbnailW = 150;
        $thumbnailH = 150;
        $allowedExts = Attachment2a::$TYPE['only_images']['array'];
        // $attachmentP = Properties::getAllOf('attachment');
        $path = env('MEDIA_ROOT_FOLDER', 'media') . "/" . date(Constant::FORMAT_YEAR_MONTH) . "/";
        try {
            $fields = Helper::getDataDbByName('fields', 'name', 'id');
            $filesUpload = $request->files;

            $attachmentRows = [];
            foreach ($filesUpload as $fieldName => $files) {
                $files = $files['toBeUploaded'];
                // Log::info($fieldName);
                $isGrouped = array_keys($files)[0] != 0; // If no grouped, the keys will be 0, 1, 2, ...
                foreach ($files as $groupId => $groupItems) {
                    $property = Properties::getFor('attachment', '_' . $fieldName);
                    $nameValidate = $fieldName . '.toBeUploaded.' . $groupId;
                    $maxFileSize = ($property['max_file_size'] == "" ? 10 : $property['max_file_size']) * 1024;
                    $maxFileCount = ($property['max_file_count'] == "" ? 10 : $property['max_file_count']);
                    $fileUploadCount = $this->countFileUploadByCondition($fieldName, $request->input('id'));
                    $fileUploadRemainingCount = $maxFileCount - $fileUploadCount;
                    $allowedFileTypes = $property['allowed_file_types'];
                    $allowedFileTypes = $this->getAllowedFileTypes($allowedFileTypes);

                    $validator = [
                        $nameValidate => 'array|max:' . $fileUploadRemainingCount,
                        $nameValidate . '.*' => 'file|' . $allowedFileTypes . '|max:' . $maxFileSize,
                    ];

                    $request->validate($validator, [
                        $nameValidate . '.max' => 'The ' . $fieldName . ' must have at most ' . $maxFileCount . ' items.',
                        $nameValidate . '.*.max' => 'The ' . $fieldName . ' must not more than ' . round($maxFileSize / 1024, 1) . ' MB.',
                    ]);

                    // Log::info($groupItems);
                    foreach ($groupItems as $file) {
                        if (!$file->getClientOriginalExtension()) {
                            toastr()->warning('File without extension cannot be uploaded!', 'Upload File Warning');
                        } else {
                            $fileName =  $file->getClientOriginalName();
                            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
                            $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);

                            // $mediaNames = Attachment::get()->pluck('filename')->toArray();
                            // $fileName = AttachmentName::slugifyImageName($fileName, $mediaNames);
                            $newFileNameWithoutExt = $fileNameWithoutExt . '-(' . Str::uuid() . ')';
                            $fileName =  $newFileNameWithoutExt . '.' . $fileExt;
                            // dd($fileName); //to test case
                            $mimeType = $file->getMimeType();
                            $imagePath = $path . $fileName;

                            Storage::disk('s3')->put($imagePath, file_get_contents($file), 'public');
                            //Only crunch if the attachment is a photo
                            if (in_array($fileExt, $allowedExts)) {
                                $thumbnailImage = Image::make($file);
                                $thumbnailImage->fit($thumbnailW, $thumbnailH);
                                $resource = $thumbnailImage->stream();
                                $thumbnailFileName = $newFileNameWithoutExt . "-{$thumbnailW}x{$thumbnailH}." . $fileExt;
                                $thumbnailPath = $path . $thumbnailFileName;
                                Storage::disk('s3')->put($thumbnailPath, $resource->__toString(), 'public');
                            }
                            // dd($fields[$fieldName]);
                            $item = [
                                'url_thumbnail' => isset($thumbnailPath) ? $thumbnailPath : "",
                                'url_media' => $imagePath,
                                'url_folder' => $path,
                                'filename' => $fileNameWithoutExt . "." . $fileExt,
                                // 'filename' => basename($imagePath),
                                'extension' => $fileExt,
                                'category' => $fields[$fieldName],
                                'sub_category' => $isGrouped ? $groupId : null,
                                'owner_id' =>  (int)Auth::user()->id,
                                'mime_type' => $mimeType,
                            ];
                            if ($object_type) $item['object_type'] = $object_type;
                            if ($object_id) $item['object_id'] = $object_id;
                            array_push($attachmentRows, $item);
                        }
                    }
                }
            }
            // Log::info($attachmentRows);
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
            toastr()->warning($ve->getMessage() . "<br/>", 'Upload File Failed');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            dd($e);
            // toastr()->warning($e, 'Upload File Warning');
        }
    }
}
