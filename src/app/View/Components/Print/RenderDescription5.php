<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class RenderDescription5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $newLine = false,
        private $colSpan = null,
        private $hiddenLabel = null,
        private $content = null,
        private $type = null,
        private $columnName = null,
        private $relationships = null,
        private $numberOfEmptyLines = 0,
        private $modelPath = null,
        private $id = null,
        private $control = null,
        private $label = null,
        private $printMode = null,
        private $item = null,
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
        $colSpan = $this->colSpan;
        $valueColSpan = $this->newLine ? $this->formatColSpanByNewLine([12, 12, 12])
        : $this->formatColSpan([24 / $colSpan, 24 / $colSpan + 1, 12 - 24 / $colSpan]);
        $params = [
            "label" => $this->label,
            "newLine" => $this->newLine,
            "hiddenLabel" => $this->hiddenLabel,
            "content" => $this->content,
            "type" => $this->type,
            "columnName" => $this->columnName,
            "relationships" => $this->relationships,
            "numberOfEmptyLines" => $this->numberOfEmptyLines,
            "modelPath" => $this->modelPath,
            "id" => $this->id,
            "control" => $this->control,
            "valueColSpan" => $valueColSpan,
            "printMode" => $this->printMode,
            "item" => $this->item,
        ];
        return view('components.print.render-description5', $params);
    }
    private function formatColSpanByNewLine(array $attributes)
    {
        return array_map(fn ($item) => 'col-span-' . $item, $attributes);
    }
    private function formatColSpan(array $attributes)
    {
        foreach ($attributes as $key => &$value) {
            if ($key == 1) $value = 'col-start-' . $value;
            else $value = 'col-span-' . $value;
        }
        return $attributes;
    }
}
