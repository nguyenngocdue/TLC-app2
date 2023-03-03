<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait TraitEntityAttachment2
{
    private function deleteAttachments($props, Request $request)
    {
        foreach ($props as $prop) {
            $propName = substr($prop, 1); //Remove first "_"
            $input = $request->input($propName);
            if (isset($input['toBeDeleted'])) {
                $toBeDeletedIds = Str::parseArray($input['toBeDeleted']);
                $this->uploadService2->destroy($toBeDeletedIds);
            } else {
                //This type doesn't have any attachment
            }
        }
    }

    private function attachOrphan($props, Request $request, $objectType, $objectId)
    {
        foreach ($props as $prop) {
            $propName = substr($prop, 1); //Remove first "_"
            $attachmentField = $request->input($propName);
            if (isset($attachmentField['toBeAttached'])) {
                $toBeAttachedIds = $attachmentField['toBeAttached'];
                // $this->uploadService2->destroy($toBeAttachedIds);
                // dd($toBeAttachedIds);
                foreach ($toBeAttachedIds as $id) {
                    $attachment = Attachment::find($id);
                    //In case if the orphan is just deleted in this transaction, ignore it from attaching
                    if (!is_null($attachment)) {
                        $attachment->object_type = $objectType;
                        $attachment->object_id = $objectId;
                        $attachment->save();
                    }
                }
            }
        }
    }

    private function uploadAttachmentWithoutParentId(Request $request)
    {
        return $this->uploadService2->store($request);
    }

    private function updateAttachmentParentId($uploadedIds, $objectType, $objectId)
    {
        foreach (array_keys($uploadedIds) as $id) {
            $attachmentRow = Attachment::find($id);
            $attachmentRow->update(['object_type' => $objectType, 'object_id' => $objectId]);
        }
    }
}
