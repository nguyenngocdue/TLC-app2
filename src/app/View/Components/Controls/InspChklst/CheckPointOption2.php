<?php

namespace App\View\Components\Controls\InspChklst;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class CheckPointOption2 extends Component
{
    // private static $singletonControlGroup = null;
    // private static $singletonOptions = null;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $line,
        private $table01Name,
        private $rowIndex,
        private $debug,
        private $type,
        private $categoryName,
        private $readOnly = false,
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
        $values = $this->line->getControlGroup->getControlValues;
        if ($values->isEmpty()) {
            return "<div class='text-center bg-red-500 rounded p-1 text-white font-bold'>"
                . "Radio group value has not been set up yet."
                . "</div>";
        }

        $options = [];
        foreach ($values as $value) {
            $options[$value->id] = $value;
        }
        // dump($options);

        return view(
            'components.controls.insp-chklst.check-point-option2',
            [
                'line' => $this->line,
                'options' => $options,
                'table01Name' => $this->table01Name,
                'rowIndex' => $this->rowIndex,
                'debug' => $this->debug,
                'categoryName' => $this->categoryName,
                'type' => $this->type,
                'readOnly' => $this->readOnly,
                'cuid' => CurrentUser::id(),
            ]
        );
    }
}
