<?php

namespace App\View\Components\Controls\HasDataSource;

use Illuminate\View\Component;

class Dropdown2 extends Component
{
    use HasDataSource;
    public function __construct(
        private $name,
        private $selected = null,
        private $multiple = false,
        private $type = null,
        private $table01Name = null,
    ) {
        $old = old($name);
        if ($old) {
            $this->selected = (is_array($old)) ? "[" . join(",", $old) . "]" : "[$old]";
        } else {
            if (isset($this->selected[0])) {
                $this->selected =  ($this->selected[0] != '[') ? "[" . $this->selected . "]" : $this->selected;
            } else {
                $this->selected = "[]";
            }
        }

        // dump($this->selected);

    }

    public function render()
    {
        $eloquentOrOracy = $this->multiple ? "oracyParams" : "eloquentParams";
        // $dataSource = $this->getDataSourceEOO($eloquentOrOracy);
        // $warning = $this->warningIfDataSourceIsEmpty($dataSource);
        // if ($warning) return $warning;

        $id = $this->name;
        // $id = $this->multiple ? substr($this->name, 0, strlen($this->name) - 2) : $this->name; // Remove parenthesis ()
        $name = $this->multiple ? $this->name . "[]" : $this->name;
        return view('components.controls.has-data-source.dropdown2', [
            // 'dataSource' => $dataSource,
            'name' => $name,
            'id' => $id,
            'selected' => $this->selected,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'className' => "bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white",
            'table' => $this->getTableEOO($eloquentOrOracy),
        ]);
    }
}
