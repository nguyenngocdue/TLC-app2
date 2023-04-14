<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentApiRequest;
use App\Models\Qaqc_insp_chklst_line;
use App\Services\Comment\CommentServiceInterface;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;

class CommentApiController extends Controller
{
    protected $commentService;
    public function __construct(CommentServiceInterface $commentService)
    {
        $this->commentService = $commentService;
    }
    public function store(StoreCommentApiRequest $request)
    {
        try {
            $comment = $this->commentService->create($request);
            return ResponseObject::responseSuccess(
                $comment,
                [],
                'Created comment successfully'
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
    public function destroy($id)
    {
        try {
            $isSuccess = $this->commentService->delete($id);
            if ($isSuccess) {
                return ResponseObject::responseSuccess(
                    null,
                    [],
                    'Deleted comment successfully'
                );
            }
            return ResponseObject::responseFail(
                'You are not the creator of the comment, so you cannot delete it!',
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
    public function getAll($id)
    {
        try {
            $comments = Qaqc_insp_chklst_line::findOrFail($id)->insp_comments;
            $commentsArray = $comments->toArray();
            foreach ($comments as $key => $comment) {
                $nameOwnerId = $comment->getOwnerId->name;
                $urlAvatar = $comment->getOwnerId->avatar->url_thumbnail ?? '';
                $commentsArray[$key]['avatar'] = $urlAvatar;
                $commentsArray[$key]['name_owner'] = $nameOwnerId;
            }
            if ($commentsArray) {
                return ResponseObject::responseSuccess(
                    $commentsArray,
                    [],
                    'Get all comments successfully'
                );
            }
            return ResponseObject::responseFail(
                'Get all comment failed',
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
}
