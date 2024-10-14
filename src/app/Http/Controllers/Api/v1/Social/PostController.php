<?php

namespace App\Http\Controllers\Api\v1\Social;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityAttachment2;
use App\Models\Post;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use TraitEntityAttachment2;
    public function index() {}
    public function store(Request $request)
    {
        try {
            if ($request->post_content || count($request->files) > 0) {
                $newPost = Post::create([
                    'content' => $request->post_content,
                    'owner_id'  => CurrentUser::id(),
                ]);
                $uploadedIds = $this->uploadAttachmentWithoutParentId($request);
                if ($uploadedIds) {
                    $this->updateAttachmentParentId($uploadedIds, Post::class, $newPost->id);
                }
                toastr()->success("Created new post successfully", "Post create");
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage(), "Post create");
        }
    }
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->back();
    }
}
