<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibStatuses;
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
        $columnName = $prop['column_name'];
        $content = $dataSource[$columnName];
        $id = $dataSource['id'];
        $label = $prop['label'];
        $control = $prop['control'];
        if ($control === 'status') {
            $libStatus = LibStatuses::getFor($this->type);
            $isContent = $dataSource[$columnName];
            $content = $isContent ? $libStatus[$isContent]['title'] : '';
        } else {
            $content = $dataSource[$columnName];
        }
        $colSpan = $prop['col_span'];
        $newLine = $prop['new_line'];
        $hiddenLabel = $prop['hidden_label'];
        $relationships = $prop['relationships'];
        return view('components.renderer.description', [
            'label' => $label,
            'colSpan' => $colSpan,
            'content' => $content,
            'control' => $control,
            'columnName' => $columnName,
            'id' => $id,
            'type' => $this->type,
            'newLine' => $newLine,
            'hiddenLabel' => $hiddenLabel,
            'modelPath' => $this->modelPath,
            'relationships' => $relationships
        ]);
    }
}
