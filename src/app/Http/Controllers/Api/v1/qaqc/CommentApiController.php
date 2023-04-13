<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentApiRequest;
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
}
