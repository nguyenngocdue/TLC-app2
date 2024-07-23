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
        private $showTitle = false,
        //Option 1
        private $dataLine = null,
        private $column = null,
        private $rendererParam = '',
        //Option 2
        private $dataSource = null,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $result = [];
        if (is_null($this->dataSource)) {
            if (!$this->dataLine) return "reference value";
            $dataIndex = $this->column['dataIndex'];
            $dataSource = $this->dataLine->$dataIndex;
        } else {
            $dataSource = $this->dataSource;
        }
        // dump($dataIndex);
        // dump($dataSource);
        foreach ($dataSource as $item) {
            $table = $item->getTable();
            $route = route($table . ".edit", $item->id);

            $id = $item->id ?? "";
            $name = $item->name ?? "";
            $idText = Str::makeId($id);
            $value = $item->status;
            $title = $idText;
            if ($this->showTitle) {
                $title = $idText . " - " . Str::limitWords($name, 5) . " (" . $item->status . ")";
            }
            $name .= " (" . $item->status . ")";

            $result[] = Blade::render("<x-renderer.status title='$title' tooltip='$name' href='$route'>$value</x-renderer.status>");
        }
        return "<ul class='p-2'><li>" . join("</li><li>", $result) . "</li></ul>";
    }
}
