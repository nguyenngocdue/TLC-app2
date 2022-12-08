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
        foreach ($dataInput as $key => $value) {
            if (str_contains($key, 'hasComment') && $value !== false) {
                $cateName = str_replace('hasComment_', '', $key);
                $item = Comment::Create(
                    [
                        'content' => $value,
                        'owner_id' => (int)$dataInput['owner_id'],
                        'category' => $nameIdsDB[$cateName]
                    ]
                );
                $ids[] = $item->id;
            };
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

    private function getIdAndNewContentCommentNeedUpdate($newDataInput, $data)
    {
        // dd(is_null($data->comments()));
        $commentsDB = $data->comments()->get();
        $commentsDB = json_decode($commentsDB, true);

        $commentCatesDB = DB::table('comment_categories')->select("id", 'name')->get();
        $json = json_decode($commentCatesDB, true);
        $idNameCatesDB = array_column($json, 'id', 'name');
        foreach (array_keys($newDataInput) as $key) {
            if (str_contains($key, 'hasComment_')) {
                $nameCate = str_replace('hasComment_', '', $key);
                $idCate = $idNameCatesDB[$nameCate];
                foreach ($commentsDB as $value) {
                    $isCheckContent = $value['content'] != $newDataInput[$key];
                    if ($isCheckContent && $value['owner_id'] * 1 === $newDataInput['owner_id'] * 1 && $value['category'] * 1 === $idCate * 1) {
                        return ['id' => $value['id'], 'newContent' => $newDataInput[$key]];
                    }
                }
            }
        }
        return [];
    }

    private function updateComment($newDataInput, $data, $updated_at)
    {
        $value = $this->getIdAndNewContentCommentNeedUpdate($newDataInput, $data, $updated_at);
        Comment::find($value['id'])->update(['content' => $value['newContent']], ['created_at' => $updated_at]);
    }
}
