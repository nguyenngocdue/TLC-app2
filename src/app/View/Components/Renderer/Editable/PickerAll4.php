<?php

namespace App\View\Components\Renderer\Editable;

use App\Utils\Support\DateTimeConcern;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class PickerAll4 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = "",
        private $placeholder = "",
        private $cell = null,
        private $onChange = null,
        private $table01Name = null,
        private $rowIndex = -1,
        private $icon = null,
        private $saveOnChange = false,
        private $readOnly = false,
        private $control = 'picker_date',
    ) {
        //In case of listeners, the data was parsed in to array
        if (is_array($this->cell)) {
            // dd($this->cell);
            $this->cell = join(",", $this->cell);
        }
        if (str_starts_with($this->cell, "No dataIndex for ")) {
            $this->cell = "";
        }
    }

    private function getPlaceholder($control)
    {
        switch ($control) {
            case "picker_datetime":
                return "DD/MM/YYYY HH:MM";
            case "picker_time":
                return "HH:MM";
            case "picker_date":
                return "DD/MM/YYYY";
            case "picker_month":
                return "MM/YYYY";
            case "picker_week":
                return "W01/YYYY";
            case "picker_quarter":
                return "Q1/YYYY";
            case "picker_year":
                return "YYYY";
            default:
                return "??? $control ???";
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->cell === 'DO_NOT_RENDER') return "";
        $this->cell = DateTimeConcern::convertForLoading($this->control, $this->cell);
        return view('components.renderer.editable.picker-all4', [
            'placeholder' => $this->getPlaceholder($this->control),
            'name' => $this->name,
            'cell' => $this->cell,
            'onChange' => $this->onChange,
            'rowIndex' => $this->rowIndex,
            'table01Name' => $this->table01Name,
            'icon' => $this->icon,
            'saveOnChange' => $this->saveOnChange,
            'readOnly' => $this->readOnly,
            'control' => $this->control,
        ]);
    }
}
