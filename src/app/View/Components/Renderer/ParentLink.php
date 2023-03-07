<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class ParentLink extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataLine,
        private $column,
    ) {
        //
        // dump($dataLine);
        // dump($column);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataLine = $this->dataLine;
        $ableStr = $this->column['dataIndex'] ?? $this->column;
        $item = $dataLine->$ableStr;
        if (!is_null($item)) {
            if (method_exists($item, 'getTable')) {
                $table = $item->getTable();
                $id = $item->id;
                $name = $item->name ?? "Nameless #$id";
                $href = route($table . ".edit", $id);
                $idStr = Str::makeId($id);
                return "<p class='p-2'><a class='text-blue-500' href='$href' title='$idStr ($table)'>$name<br/>($table)</a></p>";
            } else {
                dump("This item doesn't have getTable method.");
                dump($item);
                return;
            }
        }
        return "NO LINK FOUND";
    }
}
