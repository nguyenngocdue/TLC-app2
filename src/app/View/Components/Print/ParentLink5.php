<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class ParentLink5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $dataSource) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataSource = $this->dataSource;
        if (!is_null($dataSource)) {
            if (method_exists($dataSource, 'getTable')) {
                $table = $dataSource->getTable();
                $id = $dataSource->id;
                $name = $dataSource->name ?? "Nameless #$id";
                $href = route($table . ".edit", $id);
                $idStr = Str::makeId($id);
                return "<a class='text-blue-500' href='$href' title='$idStr ($table)'>$name ($table)</a>";
            } else {
                dump("This item doesn't have getTable method.");
                dump($dataSource);
                return;
            }
        }
        return "NO LINK FOUND";
    }
}
