<?php

namespace App\View\Components\Formula;

use App\Utils\Support\Json\Definitions;

class Duplicate_Status
{
    public function __invoke($type)
    {
        $arrayNew = array_filter(Definitions::getAllOf($type), fn ($item) => $item['new'] == 'true');
        return $arrayNew[0]['name'] ?? 'new';
    }
}
