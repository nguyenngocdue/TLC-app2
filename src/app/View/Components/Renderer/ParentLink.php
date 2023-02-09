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
        $ableStr = $this->column['dataIndex'];
        $item = $dataLine->$ableStr;
        if (!is_null($item)) {
            $table = $item->getTable();
            $id = $item->id;
            $name = $item->name ?? "Nameless";
            // dump($table);
            // dump("$item $table");
            $href = route($table . ".edit", $id);
            $idStr = Str::makeId($id);
            return "<a class='text-blue-500' href='$href' title='$idStr ($table)'>$name<br/>($table)</a>";
        }
        // return "XYZ";
    }
}
