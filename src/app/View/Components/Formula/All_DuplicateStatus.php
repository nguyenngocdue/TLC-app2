<?php

namespace App\View\Components\Formula;

use App\Utils\Support\Json\SuperDefinitions;

class All_DuplicateStatus
{
    public function __invoke($type)
    {
        // $arrayNew = array_filter(Definitions::getAllOf($type), fn ($item) => $item['new'] == 'true');
        // return $arrayNew[0]['name'] ?? 'new';
        return SuperDefinitions::getNewOf($type);
    }
}
