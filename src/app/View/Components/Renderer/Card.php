<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Card extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $style = 'bg-white',
        private $class = '',
        private $idHtml = '',
        private $px = 4,
        private $py = 4,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        // return view("components.renderer.card", ['title' => 'title', 'description' => 'desc']);
        return function (array $data) {
            $items = isset($data['attributes']["items"]) ? join("", $data['attributes']["items"]) : $data["slot"];
            $title = $data['attributes']["title"];
            $description = $data['attributes']["description"] ?? "";
            $class = $this->class;
            return "<div id='$this->idHtml' component='renderer/card' class='break-normal min-w-0 px-{$this->px} py-{$this->py}  border dark:bg-gray-800 dark:border-gray-600 rounded-lg shadow-xs $this->style $class' >" .
                (($title) ? "<h4 class='mb-4 font-semibold text-gray-600 dark:text-gray-300'>{$title} </h4>" : "") .
                "<p class='text-gray-600 dark:text-gray-300'>
                    $description
                </p>
                <p class='text-gray-600 dark:text-gray-300 break-keep'>
                    $items
                </p>
            </div>";
        };
    }
}
