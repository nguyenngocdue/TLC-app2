<?php

namespace App\View\Components\Controls\Comment;

use App\Models\User;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\Properties;
use Database\Seeders\FieldSeeder;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class CommentGroup2a extends Component
{
    private static $counter = 0;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $readOnly = false,
        private $commentableType,
        private $commentableId = '',
        private $category,
        private $debug = false,


    ) {
        //
        static::$counter++;
    }

    function getAnEmptyLine($counter, $index)
    {
        return [
            'id' => ['name' => "comments[id][comment{$counter}_ln{$index}]",],
            'position_rendered' => ['name' => "comments[position_rendered][comment{$counter}_ln{$index}]",],
            'owner_id' => ['name' => "comments[owner_id][comment{$counter}_ln{$index}]",],
            'created_at' => ['name' => "comments[created_at][comment{$counter}_ln{$index}]",],
            'content' => ['name' => "comments[content][comment{$counter}_ln{$index}]",],

            'commentable_type' => ['name' => "comments[commentable_type][comment{$counter}_ln{$index}]",],
            'commentable_id' => ['name' => "comments[commentable_id][comment{$counter}_ln{$index}]",],
            'category' => ['name' => "comments[category][comment{$counter}_ln{$index}]",],
            'category_name' => ['name' => "comments[category_name][comment{$counter}_ln{$index}]",],
            'toBeDeleted' => ['name' => "comments[toBeDeleted][comment{$counter}_ln{$index}]",],
            'comment_line_id' => "comment{$counter}_ln{$index}",
        ];
    }

    function createDataSource($comments, $commentableType, $commentableId, $category, $category_id)
    {
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        $counter = static::$counter;
        $index = 0;
        $currentUser = CurrentUser::get();
        $params = [];
        foreach ($comments as $comment) {
            $user = User::find($comment->owner_id);
            $item = $this->getAnEmptyLine($counter, $index);
            $item['id']['value'] = $comment->id;
            $item['position_rendered']['value'] = $comment->position_rendered;
            $item['owner_id']['value'] = $comment->owner_id;
            $item['owner_id']['display_name'] = $user->name;
            $item['owner_id']['avatar'] = $path . $user->avatar->url_thumbnail;
            $item['created_at']['value'] = $comment->created_at;
            $item['content']['value'] = $comment->content;

            $item['commentable_type']['value'] = $commentableType;
            $item['commentable_id']['value'] = $commentableId;
            $item['category']['value'] = $category_id;
            $item['category_name']['value'] = $category;
            $item['mine'] = $comment->owner_id == $currentUser->id;
            $params[] = $item;
            $index++;
        }
        if (!$this->readOnly) {
            $user = $currentUser;
            $item = $this->getAnEmptyLine($counter, $index);
            $item['id']['value'] = null;
            $item['position_rendered']['value'] = $user->position_rendered;
            $item['owner_id']['value'] = $user->id;
            $item['owner_id']['display_name'] = $user->name;
            $item['owner_id']['avatar'] = $path . $user->avatar->url_thumbnail;
            $item['created_at']['value'] = date(Constant::FORMAT_DATETIME_MYSQL);
            $item['content']['value'] = '';

            $item['commentable_type']['value'] = $commentableType;
            $item['commentable_id']['value'] = $commentableId;
            $item['category']['value'] = $category_id;
            $item['category_name']['value'] = $category;
            $item['mine'] = true;
            $params[] = $item;
        }
        // dump($params);
        return $params;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $category_id = FieldSeeder::getIdFromFieldName($this->category);
        $commentableTypePath = Str::modelPathFrom($this->commentableType);
        $comments = [];
        if ('' !== $this->commentableId) {
            $commentableItem = $commentableTypePath::find($this->commentableId);
            $comments = $commentableItem->{$this->category};
        }
        if ($this->debug) dump("Type: $this->commentableType($commentableTypePath) - ID: $this->commentableId - Category: $this->category #$category_id - Count: " . sizeof($comments));
        $params = $this->createDataSource($comments, $commentableTypePath, $this->commentableId, $this->category, $category_id);

        $properties = Properties::getFor('comment', $this->category);

        return view('components.controls.comment.comment-group2a', [
            'readOnly' => $this->readOnly,
            'commentableType' => $commentableTypePath,
            'commentableId' => $this->commentableId,
            'category_id' => $category_id,
            'comments' => $comments,
            'params' => $params,
            'debug' => $this->debug,
            'properties' => $properties,
        ]);
    }
}
