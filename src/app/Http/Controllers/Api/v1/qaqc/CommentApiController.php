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

    /**
     * Create Comment
     * @OA\Post (
     *     path="/api/v1/qaqc/comment",
     *     tags={"Comment"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="title",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="content",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "title":"example title",
     *                     "content":"example content"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="title", type="string", example="title"),
     *              @OA\Property(property="content", type="string", example="content"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
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
                $nameOwnerId = $comment->getOwner->name;
                $urlAvatar = $comment->getOwner->getAvatar->url_thumbnail ?? '';
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
