<?php

namespace App\View\Components\Renderer\Editable;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Dropdown extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $name = "", private $cbbDataSource = [], private $column = [])
    {
        //
        // dump($dataSource);
        $dataSource = $this->cbbDataSource;
        // Log::info($this->column);
        //Convert ["v1", "v2"] to [["value" => "v1"], ["value" => "v2"]]
        if (!is_array($dataSource[0])) $dataSource = array_map(fn ($item) => ["value" => $item], $dataSource);
        //Conver ["value" => "v1"] to [""title" => "V1", "value" => "v1"]
        // Log::info($dataSource);
        foreach ($dataSource as &$option) {
            // Log::info($option);
            if (!isset($option['title'])) $option['title'] = Str::headline($option['value']);
        }

        $sortBy = $column['sortBy'] ?? false;
        if ($sortBy) usort($dataSource, fn ($a, $b) => $a[$sortBy] <=> $b[$sortBy]);

        $this->cbbDataSource = $dataSource;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // var_dump($this->column);
        return view('components.renderer.editable.dropdown', [
            'name' => $this->name,
            'cbbDataSource' => $this->cbbDataSource,
        ]);
    }
}
