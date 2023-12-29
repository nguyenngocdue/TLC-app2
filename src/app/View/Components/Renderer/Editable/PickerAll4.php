<?php

namespace App\View\Components\Renderer\Editable;

use App\Utils\ClassList;
use App\Utils\Constant;
use App\Utils\Support\CurrentRoute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Illuminate\Support\Str;

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
        private $style = "",
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // dump(CurrentRoute::getName());
        // dump($this->table01Name);
        $minDate = null;
        if ('prod_sequences.edit' === CurrentRoute::getName()) {
            $minDate = Carbon::now()->subDays(7)->format(Constant::FORMAT_DATE_MYSQL);
        }
        // dump($minDate);

        if ($this->cell === 'DO_NOT_RENDER') return "";
        // $this->cell = DateTimeConcern::convertForLoading($this->control, $this->cell);
        return view('components.renderer.editable.picker-all4', [
            'placeholder' => Str::getPickerPlaceholder($this->control),
            'name' => $this->name,
            'cell' => $this->cell,
            'onChange' => $this->onChange,
            'rowIndex' => $this->rowIndex,
            'table01Name' => $this->table01Name,
            'icon' => $this->icon,
            'saveOnChange' => $this->saveOnChange,
            'readOnly' => $this->readOnly,
            'control' => $this->control,
            'classList' => ClassList::TEXT,
            'style' => $this->style,
            'minDate' => $minDate,
        ]);
    }
}
