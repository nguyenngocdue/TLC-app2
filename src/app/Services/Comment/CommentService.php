<?php

namespace App\Services\Comment;

use App\Models\Qaqc_insp_chklst_line;
use App\Models\User;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Services\BaseService;

class CommentService extends BaseService implements CommentServiceInterface
{
    protected $commentRepository;
    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    public function create($request)
    {
        $content = $request->content;
        $ownerId = $request->owner_id;
        $user = User::findFromCache($ownerId);
        $positionRendered = $user->getPosition->name ?? '';
        $commentTableType = Qaqc_insp_chklst_line::class;
        return $this->commentRepository->create([
            'content' => $content,
            'owner_id' => $ownerId,
            'position_rendered' => $positionRendered,
            'commentable_type' => $commentTableType,
            'commentable_id' => $request->commentable_id,
            'category' => 13, // field insp_comments
            'user' => $user,
        ]);
    }
    public function delete($id)
    {
        $commentRepository = $this->commentRepository;
        $comment = $commentRepository->find($id);
        if ($comment->owner_id == auth()->user()->id) {
            $this->commentRepository->delete($id);
            return true;
        }
        return false;
    }
}
