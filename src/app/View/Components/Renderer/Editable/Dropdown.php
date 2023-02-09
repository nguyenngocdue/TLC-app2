<?php

namespace App\View\Components\Renderer\Editable;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Dropdown extends Component
{
    private $selected;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = "",
        private $cbbDataSource = [],
        private $sortBy = false,
        private $strFn = false,
        private $cell = null,
    ) {
    }

    private function makeDataSource()
    {
        $dataSource = $this->cbbDataSource;
        //Convert ["v1", "v2"] to [["value" => "v1"], ["value" => "v2"]]
        if (!is_array($dataSource[0])) $dataSource = array_map(fn ($item) => ["value" => $item], $dataSource);
        //Convert ["value" => "v1"] to ["title" => "V1", "value" => "v1"]
        foreach ($dataSource as &$option) {
            // Log::info($option);
            if (!isset($option['title'])) {
                $strFn = $this->strFn ? $this->strFn : "headline";
                $option['title'] = Str::{$strFn}($option['value']);
            }
        }
        if ($this->sortBy) usort($dataSource, fn ($a, $b) => $a[$this->sortBy] <=> $b[$this->sortBy]);
        return $dataSource;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->cell === 'DO_NOT_RENDER') return "";
        // dump($this->cell);
        if (is_array($this->cell)) {
            $this->selected = $this->cell['value'] ?? null;
            $this->cbbDataSource = $this->cell['cbbDS'] ?? [];
        } else {
            $this->selected = $this->cell;
        }
        $dataSource = $this->makeDataSource();
        // var_dump($this->column);
        return view('components.renderer.editable.dropdown', [
            'name' => $this->name,
            'cbbDataSource' => $dataSource,
            'selected' => $this->selected,
        ]);
    }
}
