<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Pivot2 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $rendererParam,
        private $dataLine,
        private $column,
        // private $pivot_column,
    ) {
        //
        // dump($this->rendererParam);
        // dump($this->dataLine);
        // dd($column);
        // dump($this->pivot_column);
    }

    private static $singleton = [];

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $json = json_decode($this->rendererParam);
        $column = $json->column;
        // dump($json->conditions);

        [$param1, $operator, $param111] = $json->conditions[0];
        if (str_contains($param1, ".")) {
            [$param1a, $param1b] = explode(".", $param1);

            $value1 =  ($this->dataLine->$param1a) ? $this->dataLine->$param1a->$param1b : "NULL";
        } else {
            $value1 =  $this->dataLine->$param1;
        }

        [$param1, $operator, $param222] = $json->conditions[1];
        if (str_contains($param1, ".")) {
            [$param1a, $param1b] = explode(".", $param1);
            $value2 =  ($this->dataLine->$param1a) ? $this->dataLine->$param1a->$param1b : "NULL";
        } else {
            $value2 =  $this->dataLine->$param1;
        }

        $params =  [
            'param1' => $param111,
            'param2' => $param222,
            'value1' => $value1,
            'value2' => $value2,
            'column' => $column,
        ];

        return view('components.renderer.pivot2', $params);
    }
}
