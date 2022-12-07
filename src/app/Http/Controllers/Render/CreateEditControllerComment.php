<?php

namespace App\Http\Controllers\Render;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;

trait CreateEditControllerComment
{

    private function saveAndGetIdsComments($dataInput)
    {

        $commentCatesDB = DB::table('comment_categories')->select("id", 'name')->get();
        $json = json_decode($commentCatesDB, true);
        $nameIdsDB = array_column($json, 'id', 'name');
        $ids = [];
        foreach ($dataInput['content'] as $key => $content) {
            $item = Comment::create(
                [
                    'content' => $content,
                    'owner_id' => (int)$dataInput['owner_id'],
                    'category' => $nameIdsDB[$dataInput['category'][$key]]
                ]
            );
            $ids[] = $item->id;
        }
        return $ids;
    }
    private function setCommentsParent($idsComment, $data)
    {
        foreach ($idsComment as $id) {
            if (!is_null($db = Comment::find($id))) {
                $data->comments()->save($db);
            }
        }
    }

    private function updateComments($newDataInput, $data, $updated_at)
    {
        $commentsDB = $data->comments()->get();
        $commentsDB = json_decode($commentsDB, true);

        $commentCatesDB = DB::table('comment_categories')->select("id", 'name')->get();
        $json = json_decode($commentCatesDB, true);

        $idNameCateCommentsDB = array_column($json,  'name', 'id');

        foreach ($commentsDB as  $value) {
            $nameCate =  $idNameCateCommentsDB[$value['category']];
            $index = array_search($nameCate, $newDataInput['category']);
            $newContent = $newDataInput['content'][$index];
            if ($value['content'] != $newContent) {
                Comment::find($value['id'])->update(['content' => $newContent, 'created_at' => $updated_at]);
            }
        }
    }
}
