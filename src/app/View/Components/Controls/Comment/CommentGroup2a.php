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
        private $commentableType,
        private $category,
        private $readOnly = false,
        private $commentableId = '',
        private $debug = false,
        private $commentIds = null,
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

    function getOldComment($counter, $index)

    {
        $name = "comment{$counter}_ln{$index}";
        $comments = old('comments');
        if (isset($comments[$name])) return $comments[$name]['content'];
        return '';
    }

    function createDataSource($comments, $commentableType, $commentableId, $category, $category_id)
    {
        $counter = static::$counter;
        $index = 0;
        $currentUser = CurrentUser::get();
        $params = [];
        foreach ($comments as $comment) {
            $user = User::findFromCache($comment->owner_id);
            $item = $this->getAnEmptyLine($counter, $index);
            $item['id']['value'] = $comment->id;
            $item['position_rendered']['value'] = $comment->position_rendered;
            $item['owner_id']['value'] = $comment->owner_id;
            $item['owner_id']['display_name'] = $user->name;
            $item['owner_id']['avatar'] = $user->getAvatarThumbnailUrl();
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
            $oldValue = $this->getOldComment($counter, $index);
            $user = $currentUser;
            $item = $this->getAnEmptyLine($counter, $index);
            $item['id']['value'] = null;
            $item['position_rendered']['value'] = $user->getPosition->name ?? '';
            $item['owner_id']['value'] = $user->id;
            $item['owner_id']['display_name'] = $user->name;
            $item['owner_id']['avatar'] =  $user->getAvatarThumbnailUrl();
            $item['created_at']['value'] = date(Constant::FORMAT_DATETIME_MYSQL);
            $item['content']['value'] = $oldValue;

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
        $commentableItem = new ($commentableTypePath); //::find($this->commentableId);
        if ('' !== $this->commentableId) {
            $commentableItem->id = $this->commentableId;
            if (is_null($this->commentIds)) {
                $comments = $commentableItem->{$this->category};
            } else {
                $comments = $commentableItem->getMorphManyByIds($this->commentIds, $this->category);
            }
        }
        if ($this->debug) dump("Type: $this->commentableType($commentableTypePath) - ID: $this->commentableId - Category: $this->category #$category_id - Count: " . sizeof($comments));
        $params = $this->createDataSource($comments, $commentableTypePath, $this->commentableId, $this->category, $category_id);

        // $properties = Properties::getFor('comment', $this->category);

        return view('components.controls.comment.comment-group2a', [
            'readOnly' => $this->readOnly,
            'commentableType' => $commentableTypePath,
            'commentableId' => $this->commentableId,
            'category_id' => $category_id,
            'comments' => $comments,
            'params' => $params,
            'debug' => $this->debug,
            // 'properties' => $properties,

        ]);
    }
}
