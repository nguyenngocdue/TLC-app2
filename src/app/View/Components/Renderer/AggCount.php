<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class AggCount extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $rendererUnit = '',
        private $renderRaw = false,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return function (array $data) {
            $json = json_decode($data['slot']);
            if (!is_array($json)) $json = [$json];
            $count = sizeof($json);
            if ($count == 0) return "";
            $unit = $this->rendererUnit ? $this->rendererUnit : "item";
            $str = Str::of($unit)->plural($count);
            $str = $count . " " . $str;
            $names = [];
            foreach ($json as $index => $item) {
                if (!is_null($item)) {
                    $name = $item->name ?? "Nameless #" . $item->id;
                    $names[] = (1 + $index) . ". " . $name;
                }
            }
            $title = join("\n", $names);
            if ($this->renderRaw) return $str;
            return "<div class='text-center' title='$title'><x-renderer.tag>$str</x-renderer.tag></div>";
        };
    }
}
