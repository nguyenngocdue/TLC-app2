<?php

namespace App\Utils\SendMaiAndNotification;

use App\Utils\Support\Json\Definitions;

trait CheckDefinitionsNew
{
    public function isDefinitionNew($currentValue)
    {
        $type = $currentValue['entity_type'];
        $status = $currentValue['status'];
        $definitions = Definitions::getAllOf($type)['new'] ?? ['name' => '', 'new' => true];
        array_shift($definitions);
        $arrayCheck = array_keys(array_filter($definitions, fn ($item) => $item));
        return sizeof($arrayCheck) == 0 || in_array($status, $arrayCheck);
    }
}
