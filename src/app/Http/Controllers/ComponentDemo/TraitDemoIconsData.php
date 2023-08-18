<?php

namespace App\Http\Controllers\ComponentDemo;

use Illuminate\Support\Facades\Log;

trait TraitDemoIconsData
{
    function getIconsColumns()
    {
        return [
            [
                'dataIndex' => 'name',
            ],
            [
                'dataIndex' => 'solid',
                'align' => 'center',
            ],
            [
                'dataIndex' => 'regular',
                'align' => 'center',
            ],
            [
                'dataIndex' => 'light',
                'align' => 'center',
            ],
            [
                'dataIndex' => 'thin',
                'align' => 'center',
            ],
            [
                'dataIndex' => 'duotone',
                'align' => 'center',
            ],
        ];
    }

    function getIconsDataSource()
    {
        $icons = config("icons");
        $result = [];
        foreach ($icons as $icon) {
            $result[] = [
                'name' => $icon,
                'solid' => "<i class='text-5xl fa-solid fa-$icon'/>",
                'regular' => "<i class='text-5xl fa-regular fa-$icon'/>",
                'light' => "<i class='text-5xl fa-light fa-$icon'/>",
                'thin' => "<i class='text-5xl fa-thin fa-$icon'/>",
                'duotone' => "<i class='text-5xl fa-duotone fa-$icon' style='--fa-primary-color: orange; --fa-secondary-color: green;'/>",
            ];
        }

        return $result;
    }
}
