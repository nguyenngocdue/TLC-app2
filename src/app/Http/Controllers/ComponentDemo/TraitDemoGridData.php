<?php

namespace App\Http\Controllers\ComponentDemo;

use Illuminate\Support\Facades\Log;

trait TraitDemoGridData
{
    function getGridData($tableDataSource)
    {
        return array_map(fn ($item) => [
            'name' => $item['client'],
            'avatar' => $item['avatar'] ?? null,
            'position_rendered' => $item['amount'],
            'gray' => $item['disabled'] ?? false,
        ], $tableDataSource);
    }
}
