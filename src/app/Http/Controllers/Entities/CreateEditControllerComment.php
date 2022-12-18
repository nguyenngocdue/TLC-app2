<?php

namespace App\Http\Controllers\Entities;

use App\Helpers\Helper;
use App\Models\Attachment;
use App\Models\Attachment_category;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

trait CreateEditControllerComment
{

    private function saveAndGetIdsComments($dataInput)
    {
        $nameIdsDB = Helper::getDataDbByName('attachment_categories', 'name', 'id');
        dump($nameIdsDB);
        $ids = [];
        foreach ($dataInput as $key => $value) {
            // dd($value);
            if (str_contains($key, 'newComment_')) {
                $cateName = str_replace('newComment_', '', $key);
                // dd($key, $cateName, $dataInput[$cateName]);
                if (!is_null($value) || isset($dataInput[$cateName]) && count($dataInput[$cateName]) > 0) {
                    $item = Comment::Create(
                        [
                            'content' => $value,
                            'position_rendered' => $dataInput['position_rendered'],
                            'owner_id' => (int)$dataInput['owner_id'],
                            'category' => $nameIdsDB[$cateName],
                        ]
                    );
                    $ids[] = $item->id;
                }
            };
        }
        return $ids;
    }
    private function setMediaCommentsParent($idsComment, $id_ColNameMedia)
    {
        $idsNamesAttDB = Helper::getDataDbByName('attachment_categories', 'id', 'name');
        foreach ($idsComment as $id) {
            $comment = Comment::find($id);
            foreach ($id_ColNameMedia as $key => $value) {
                if ($value === $idsNamesAttDB[$comment->category]) {
                    $comment->media()->save(Attachment::find($key));
                }
            }
        }
    }
    private function setCommentsParent($idsComment, $data)
    {
        foreach ($idsComment as $id) {
            if (!is_null($db = Comment::find($id))) {
                $data->comments()->save($db);
            }
        }
    }
    public function delComments($dataInput)
    {
        foreach ($dataInput as $key => $value) {
            if (str_contains($key, 'comment_deleted_')) {
                $itemComment = Comment::find($value * 1);
                if (!is_null($itemComment)) {
                    $idsAtt = $itemComment->media()->pluck('id');
                    $delItem = $itemComment->delete();
                    foreach ($idsAtt as $id) {
                        Attachment::find($id)->delete();
                    }
                }
            }
        }
    }
}
