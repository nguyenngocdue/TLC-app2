<?php

namespace App\View\Components\Controls;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\ClassList;
use App\Utils\Support\Entities;
use Illuminate\View\Component;

class EntityTypeDropdown2 extends Component
{
    // private $value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $modelPath,
        private $value = null,
        private $readOnly = false,
    ) {
        //
    }

    public function render()
    {
        $all = Entities::getAllPluralNames();
        $items = [];
        foreach ($all as $key) {
            $title = LibApps::getFor($key)['title'];
            if (!str_contains($title, "IS MISSING")) {
                // dump($title);
                $items[$key] = $title;
            }
        }
        uasort($items, fn($a, $b) => strcasecmp($a, $b));
        $items[''] = '';

        // dd($items);
        return view("components.controls.entity-type-dropdown2", [
            'options' => $items,
            'name' => $this->name,
            'value' => $this->value,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
        ]);
    }
}
