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
        private $idHtml = '',
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
            return "<div id='$this->idHtml' class='break-normal min-w-0 p-4  border rounded-lg shadow-xs $this->style' >" .
                (($title) ? "<h4 class='mb-4 font-semibold text-gray-600 dark:text-gray-300'>{$title} </h4>" : "") .
                "<p class='text-gray-600 dark:text-gray-400'>
                    $description
                </p>
                <p class='text-gray-600 dark:text-gray-400 break-keep'>
                    $items
                </p>
            </div>";
        };
    }
}
