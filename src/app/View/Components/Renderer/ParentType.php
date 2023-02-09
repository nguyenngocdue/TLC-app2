<?php

namespace App\View\Components\Renderer;

use App\Utils\Support\Json\Relationships;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class ParentType extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $selected = "",
        private $multiple = false,
        private $type,
    ) {
    }



    private function getDataSource()
    {
        // dump($this->getMetadata());
        $result = [
            ['id' => 'a', 'name' => 'Fortune'],
            ['id' => 'b', 'name' => 'Due'],
            ['id' => 'c', 'name' => 'Canh'],
        ];
        return $result;
    }

    private function renderJS($tableName)
    {
        $k = [$tableName => $this->getDataSource(),];
        $str = "";
        $str .= "<script>";
        $str .= " k = {...k, ..." . json_encode($k) . "};";
        $str .= "</script>";
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
            'className' => "bg-white border border-gray-300 text-sm rounded-lg block mt-1 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white",
        ];
        $this->renderJS($tableName);
        // dump($params);
        return view('components.controls.has-data-source.dropdown2', $params);
    }
}
