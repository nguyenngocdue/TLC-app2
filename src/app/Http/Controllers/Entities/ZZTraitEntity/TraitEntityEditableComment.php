<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Comment;
use Illuminate\Http\Request;

trait TraitEntityEditableComment
{
    function processComments(Request $request, $commentableId = null)
    {
        $comments = $request->input('comments');
        if (is_null($comments)) return;
        $result = [];
        foreach ($comments as $fieldName => $fields) {
            foreach ($fields as $key => $value) {
                $result[$key][$fieldName] = $value;
                //In case inserting new parent
                if ($fieldName == 'commentable_id' && is_null($value)) {
                    $result[$key]['commentable_id'] = $commentableId;
                }
            }
        }
        foreach ($result as $line) {
            if (is_null($line['id']) && is_null($line['content'])) continue;
            //Insert
            if (is_null($line['id'])) {
                Comment::create($line);
            } else { //Update
                Comment::find($line['id'])->update($line);
            }
        }
    }
}
