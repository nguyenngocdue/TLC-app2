<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

trait TraitGetOptionPrint
{
    use TraitViewAllFunctions;

    public function getValueOptionPrint()
    {
        [,,,,,,, $optionPrint] = $this->getUserSettingsViewAll();
        return $optionPrint;
    }
    public function getLayoutPrint($valueOptionPrint, $view = 'props')
    {
        $common_landscape = "width:1400px; min-height:1000px;";
        $common_portrait = "width:1000px; min-height:1400;";

        $styles = [
            "props" => [
                "landscape" => $common_landscape,
                "portrait" => $common_portrait,
                // "landscape" => 'width:1400px; min-height:1000px;', //'w-[1400px] min-h-[1000px]',
                // "portrait" => 'width:1000px; min-height:1355px;', //'w-[1000px] min-h-[1355px]',
            ],
            "check-list" => [
                "landscape" => $common_landscape,
                "portrait" => $common_portrait,
                // "landscape" => 'width:1414px; min-height:1000px;', //'w-[1414px] min-h-[1000px]',
                // "portrait" => 'width:1000px; min-height:1405px;', //'w-[1000px] min-h-[1405px]',
            ],
            "check-sheet" => [
                "landscape" => $common_landscape,
                "portrait" => $common_portrait,
                // "landscape" => 'width:1415px; min-height:1080px;', //'w-[1415px] min-h-[1080px]',
                // "portrait" => 'width:1000px; min-height:1360px;', //'w-[1000px] min-h-[1360px]',
            ],
        ];
        $valueOptionPrint = $valueOptionPrint ?: 'portrait';
        // dd("Layout [$view] [$valueOptionPrint]");
        return $styles[$view][$valueOptionPrint];
    }
}
