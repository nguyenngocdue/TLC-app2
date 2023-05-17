<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

trait TraitGetOptionPrint
{
    use TraitViewAllFunctions;

    public function getValueOptionPrint()
    {
        [,,,,,,, $optionPrint] = $this->getUserSettings();
        return $optionPrint;
    }
    public function getLayoutPrint($valueOptionPrint, $view = 'props')
    {
        $value = match ($view) {
            'props' => ['w-[1400px] min-h-[1000px]', 'w-[1000px] min-h-[1355px]'],
            'check-list' => ['w-[1414px] min-h-[1000px]', 'w-[1000px] min-h-[1405px]'],
            'check-sheet' => ['w-[1415px] min-h-[1080px]', 'w-[1000px] min-h-[1360px]'],
        };
        switch ($valueOptionPrint) {
            case 'landscape':
                $layout = $value[0];
                break;
            case 'portrait':
            default:
                $layout = $value[1];
                break;
        }
        return $layout;
    }
}
