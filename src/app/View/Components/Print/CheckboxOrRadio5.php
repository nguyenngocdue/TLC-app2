<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class CheckboxOrRadio5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $value,
        private $relationships,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $columnRender = $this->relationships['renderer_view_all_param'] ?: 'name';
        $colSpan = $this->relationships['radio_checkbox_colspan'];
        $dataSource = array_column($this->value, $columnRender);
        return view('components.print.checkbox-or-radio5', [
            'dataSource' => $dataSource,
            'colSpan' => $colSpan,
        ]);
    }
}
