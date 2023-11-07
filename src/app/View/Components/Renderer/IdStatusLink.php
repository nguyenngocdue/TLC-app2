<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class IdStatusLink extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataLine,
        private $column,
        private $rendererParam = '',
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // if ($this->rendererParam === '') return "renderer_param ?";
        // $rendererParam = $this->rendererParam;
        $result = [];

        $dataIndex = $this->column['dataIndex'];
        if (str_contains($dataIndex, "()")) {
            $dataIndex = substr($dataIndex, 0, strlen($dataIndex) - 2);
            $dataSource = $this->dataLine->{$dataIndex}();
        } else {
            $dataSource = $this->dataLine->$dataIndex;
        }
        // dump($dataIndex);
        // dump($dataSource);
        // if (is_null($dataSource)) return;
        foreach ($dataSource as $item) {
            $table = $item->getTable();
            $route = route($table . ".edit", $item->id);

            $id = $item->id ?? "";
            $name = $item->name ?? "";
            $name .= " (" . $item->status . ")";
            $idText = Str::makeId($id);
            $value = $item->status;

            // $value = $item->$rendererParam;
            $result[] = Blade::render("<x-renderer.status title='$idText' href='$route' tooltip='$name'>$value</x-renderer.status>");
            // $result[] = "<a title='#{$id}' href='$route' class='hover:bg-blue-200 rounded p-1 whitespace-nowrap'>" . $id . "</a>";
        }
        return "<p class='p-2'>" . join(" ", $result) . "</p>";
    }
}
