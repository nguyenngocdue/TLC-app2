<?php

namespace App\View\Components\Controls\Comment;

use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\Properties;
use Carbon\Carbon;
use Illuminate\View\Component;

class Comment2a extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $comment = null,
        private $debug = false,
        // private $properties = [],
        private $readOnly = false,
    ) {
        //
    }

    private function getTitle($name, $position, $created_at)
    {
        $title = "$name ($position)";
        $humanReadable = "now";
        if (!is_null($this->comment['id']['value'])) {
            $humanReadable = Carbon::createFromFormat(Constant::FORMAT_DATETIME_MYSQL, $created_at)->diffForHumans();
            $title .= " $humanReadable said";
        }
        $title .= ":";
        return [$title, $humanReadable];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $name =  $this->comment['owner_id']['display_name'];
        $position = $this->comment['position_rendered']['value'];
        $category_name = $this->comment['category_name']['value'];
        $created_at = $this->comment['created_at']['value'];
        [$title, $humanReadable] = $this->getTitle($name, $position, $created_at);

        $hasNotCreated = is_null($this->comment['id']['value']);
        $user = CurrentUser::get();
        $differentOwner = $this->comment['owner_id']['value'] !== $user->id;
        $readOnly = $this->readOnly || $differentOwner;
        // dump($readOnly);
        // dump($this->properties);
        $properties = Properties::getFor('comment', $category_name);
        $allowed_to_delete = $properties['allowed_to_delete'] == true;
        $deletable = !$this->readOnly && $allowed_to_delete && !$differentOwner && !$hasNotCreated;
        // dump($deletable);

        $category = $properties['field_name'];
        $category_id = $properties['field_id'];
        $legendTooltip = "Category: $category (#$category_id) (#" . $this->comment['id']['value'] . ")";

        return view(
            'components.controls.comment.comment2a',
            [
                'comment' => $this->comment,
                // 'debug' => $this->debug,
                'input_or_hidden' => $this->debug ? 'input' : 'hidden',
                "title" => $title,
                'humanReadable' => $humanReadable,
                'properties' => $properties,
                'readOnly' => $readOnly,
                'deletable' => $deletable,
                'legendTooltip' => $legendTooltip,
            ]
        );
    }
}
