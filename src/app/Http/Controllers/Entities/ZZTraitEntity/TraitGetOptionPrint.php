<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

trait TraitGetOptionPrint
{
    use TraitViewAllFunctions;

    function getValueOptionPrint()
    {
        [,,,,,,, $optionPrint] = $this->getUserSettings();
        return $optionPrint;
    }
}
