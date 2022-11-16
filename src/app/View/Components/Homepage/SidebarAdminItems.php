<?php

namespace App\View\Components\Homepage;

use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;

class SidebarAdminItems
{
    public static function getAll($svg)
    {
        $currentType = CurrentRoute::getTypeSingular();
        $isActive = ($currentType === 'permission');

        return [
            [
                "title" => "Permission",
                "type" => "ppppp",
                "icon" => $svg['modals'],
                "isActive" => $isActive,
                "children" => [
                    [
                        'title' => "Permissions",
                        'href' => "/dashboard/admin/permissions",
                    ],
                    [
                        'title' => "Roles",
                        'href' =>  "/dashboard/admin/roles",
                    ],
                    [
                        'title' => "Rolesets",
                        'href' => "/dashboard/admin/role_sets",
                    ],
                    [
                        'title' => "-",
                    ],
                    [
                        'title' => "Set Permissions to Roles",
                        'href' => "/dashboard/admin/setpermissions",
                    ],
                    [
                        'title' => "Set Roles to Rolesets",
                        'href' => "/dashboard/admin/setroles",
                    ],
                    [
                        'title' => "Set Rolesets to Users",
                        'href' => "/dashboard/admin/setrolesets",
                    ],
                ],
            ]
        ];
    }
}
