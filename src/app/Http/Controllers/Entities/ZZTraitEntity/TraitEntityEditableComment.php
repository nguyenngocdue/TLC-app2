<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitEntityEditableComment
{
    function reArrangeComments(Request $request, $commentableId = null)
    {
        $comments = $request->input('comments');
        if (is_null($comments)) return;
        $result = [];
        foreach ($comments as $fieldName => $fields) {
            foreach ($fields as $key => $value) {
                $result[$key][$fieldName] = $value;
            }
        }

        $oldInput = $request->input();
        $oldInput['comments'] = $result;
        $request->request->replace($oldInput);
    }

    function processComments(Request $request, $commentableId = null)
    {
        $comments = $request->input('comments');
        if (is_null($comments)) return;

        foreach ($comments as $line) {
            if (is_null($line['id']) && is_null($line['content'])) continue;
            // dump($line);
            if (is_null($line['id'])) {
                //Insert
                if (is_null($line['commentable_id'])) {
                    $line['commentable_id'] = $commentableId;
                }
                Comment::create($line);
            } else { //Update or Delete
                $comment = Comment::find($line['id']);
                // dump($line);
                if (isset($line['toBeDeleted']) && $line['toBeDeleted'] !== 'false') {
                    Log::info("Delete " . $line['id']);
                    $comment->delete();
                } else {
                    Log::info("Update " . $line['id']);
                    $comment->update($line);
                }
            }
        }
    }
}
