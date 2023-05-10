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
        private $class = 'bg-white border',
        private $idHtml = '',
        private $px = 4,
        private $py = 4,
        private $tooltip = "",
        private $icon = null,
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
            $result = "<fieldset id='$this->idHtml' component='renderer/card' 
                class='$class break-normal min-w-0 px-{$this->px} py-{$this->py} dark:bg-gray-800 dark:border-gray-600 rounded-lg shadow-xs'>";
            if ($title) {
                $result .= "<legend title='$this->tooltip'>";
                $result .=  "<h4 class='px-2 font-medium text-gray-600 dark:text-gray-300'>";
                $result .= $this->icon ? "<i class='$this->icon text-blue-800 pr-2 rounded'></i>" : "";
                $result .= $title;
                $result .= "</h4>";
                $result .= "</legend>";
            }
            if ($description) $result .= $description;
            $result .= "<p class='text-gray-600 dark:text-gray-300 break-keep'>";
            $result .= $items;
            $result .= "</p>";
            $result .= "</fieldset>";

            // return "<div id='$this->idHtml' component='renderer/card' class='break-normal min-w-0 px-{$this->px} py-{$this->py}  border dark:bg-gray-800 dark:border-gray-600 rounded-lg shadow-xs $this->style $class' >" .
            //     (($title) ? "<h4 class='mb-4 font-semibold text-gray-600 dark:text-gray-300'>{$title} </h4>" : "") .
            //     "<p class='text-gray-600 dark:text-gray-300'>
            //         $description
            //     </p>
            //     <p class='text-gray-600 dark:text-gray-300 break-keep'>
            //         $items
            //     </p>
            // </div>";
            return $result;
        };
    }
}
