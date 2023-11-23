<?php

namespace App\View\Components\Controls;

use App\Utils\ClassList;
use Illuminate\View\Component;

class ParentType2 extends Component
{
    use TraitMorphTo;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $type,  //<< Type for morphedTo
        private $selected = "",
        private $multiple = false,
        private $readOnly = false,
        private $allowClear = false,
    ) {
        if (old($name)) $this->selected = old($name);
    }

    private function getDataSource()
    {
        return $this->getAllTypeMorphMany();
    }

    private function renderJS($tableName)
    {
        $k = [$tableName => $this->getDataSource(),];
        $str = "\n";
        $str .= "<script>";
        $str .= " k = {...k, ..." . json_encode($k) . "};";
        $str .= "</script>";
        $str .= "\n";
        echo $str;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $tableName = "morph_to_" . $this->name;
        $params = [
            'name' => $this->name,
            'id' => $this->name,
            'selected' => json_encode([$this->selected]),
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'readOnly' => $this->readOnly,
            'classList' => ClassList::DROPDOWN,
            // 'entity' => $this->type,
            'allowClear' => $this->allowClear,
        ];
        $this->renderJS($tableName);
        // dump($params);
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
