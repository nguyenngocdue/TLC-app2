<?php

namespace App\View\Components\Controls\InspChklst;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class CheckPointOption extends Component
{
    private static $singletonControlGroup = null;
    private static $singletonOptions = null;
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
        $line = $this->line;
        if (!isset(static::$singletonControlGroup)) {
            static::$singletonControlGroup = $line->getControlGroup;
        }
        if (static::$singletonControlGroup) {
            $eloquentParamsControlGroup = $line::$eloquentParams['getControlGroup'];
            $eloquentParamsControlValue = $line::$eloquentParams['getControlValue'];
            $modelPath = $eloquentParamsControlValue[1];
            $keyIdModelControlValue = $eloquentParamsControlValue[2];
            $keyIdModelControlGroup = $eloquentParamsControlGroup[2];
            if (!isset(static::$singletonOptions)) {
                static::$singletonOptions = $modelPath::where($keyIdModelControlGroup, static::$singletonControlGroup->id)->get();
            }
            $options = static::$singletonOptions->pluck('name', 'id',);
        } else {
            return "CONTROL GROUP ID IS NULL";
        }

        $class = [
            1 => 'peer-checked:bg-green-300 peer-checked:text-green-700',
            2 => 'peer-checked:bg-pink-300 peer-checked:text-pink-700',
            3 => 'peer-checked:bg-gray-300 peer-checked:text-gray-700',
            4 => 'peer-checked:bg-orange-300 peer-checked:text-orange-700',
            5 => 'peer-checked:bg-green-300 peer-checked:text-green-700',
            6 => 'peer-checked:bg-pink-300 peer-checked:text-pink-700',
            7 => 'peer-checked:bg-gray-300 peer-checked:text-gray-700',
            8 => 'peer-checked:bg-orange-300 peer-checked:text-orange-700',
        ];

        return view(
            'components.controls.insp-chklst.check-point-option',
            [
                'line' => $this->line,
                'options' => $options,
                'table01Name' => $this->table01Name,
                'rowIndex' => $this->rowIndex,
                'class' => $class,
                'keyIdModelControlValue' => $keyIdModelControlValue ?? '',
                'type' => $this->type,
                'readOnly' => $this->readOnly,
                'cuid' => CurrentUser::id(),
            ]
        );
    }
}
