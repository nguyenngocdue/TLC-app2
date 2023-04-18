<?php

namespace App\View\Components\Controls;

use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
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
        private $comment,
        private $debug = false,
        private $properties = [],
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
            $title .= " at $humanReadable";
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
        $created_at = $this->comment['created_at']['value'];
        [$title, $humanReadable] = $this->getTitle($name, $position, $created_at);

        $user = CurrentUser::get();
        $differentOwner = $this->comment['owner_id']['value'] !== $user->id;
        $readOnly = $this->readOnly || $differentOwner;
        // dump($readOnly);

        return view(
            'components.controls.comment2a',
            [
                'comment' => $this->comment,
                // 'debug' => $this->debug,
                'input_or_hidden' => $this->debug ? 'input' : 'hidden',
                "title" => $title,
                'humanReadable' => $humanReadable,
                'properties' => $this->properties,
                'readOnly' => $readOnly,
            ]
        );
    }
}
