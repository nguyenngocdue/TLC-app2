<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Description extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $prop,
        private $dataSource,
        private $modelPath,
        private $type,
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
        $dataSource = $this->dataSource;
        $prop = $this->prop;
        dd($prop);
        $columnName = $prop['column_name'];
        $content = $dataSource[$columnName];
        $id = $dataSource['id'];
        $label = $prop['label'];
        $control = $prop['control'];
        $colSpan = $prop['col_span'];
        $relationships = $prop['relationships'];
        return view('components.renderer.description', [
            'label' => $label,
            'colSpan' => $colSpan,
            'content' => $content,
            'control' => $control,
            'columnName' => $columnName,
            'id' => $id,
            'type' => $this->type,
            'modelPath' => $this->modelPath,
            'relationships' => $relationships
        ]);
    }
}
