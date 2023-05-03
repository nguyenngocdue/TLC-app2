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

        $table = $dataLine->getTable();
        $modelPath = Str::modelPathFrom($table);
        // dump($dataLine);
        // dump($dataLine->getRelations());
        $relations = $dataLine->getRelations();
        if (isset($relations[$modelPath])) {
            $parent = $relations[$modelPath];
            $parentTable = $parent->getTable();
            $parentId = $parent->id;
            // $idStr = Str::makeId($parentId);
            $href = route($parentTable . ".edit", $parentId);
            $name = $parent->name;
            return "<p class='p-2'><a class='text-blue-500' href='$href' title='$name'>$parentTable/$parentId</a></p>";
        }
        // dump($this->column);
        // dump($dataLine->getParent()->get());
        // $strAble = $this->column['dataIndex'] ?? $this->column;
        // $items = $dataLine->$strAble()->get();
        // if (sizeof($items) > 0) {
        //     $item = $items[0];
        //     if (method_exists($item, 'getTable')) {
        //         $table = $item->getTable();
        //         $id = $item->id;
        //         $name = $item->name ?? "Nameless #$id";
        //         $href = route($table . ".edit", $id);
        //         $idStr = Str::makeId($id);
        //         return "<p class='p-2'><a class='text-blue-500' href='$href' title='$idStr ($table)'>$name</a></p>";
        //     } else {
        //         dump("This item doesn't have getTable method.");
        //         dump($item);
        //         return;
        //     }
        // }
        return "<div class='px-2'>NO PARENT FOUND</div>";
    }
}
