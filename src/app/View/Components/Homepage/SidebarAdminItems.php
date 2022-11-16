<?php

namespace App\View\Components\Homepage;

class SidebarAdminItems
{
    public static function getAll($svg)
    {
        // return [];

        return [
            [
                "title" => "Permission",
                "type" => "ppppp",
                "icon" => $svg['modals'],
                "isActive" => false, //$isActive,
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
                        'title' => "-",
                    ],
                    [
                        'title' => "Role Sets",
                        'href' => "/dashboard/admin/role_sets",
                    ],
                    [
                        'title' => "Set Permissions",
                        'href' => "/dashboard/admin/setpermissions",
                    ],
                    [
                        'title' => "Set Roles",
                        'href' => "/dashboard/admin/setroles",
                    ],
                    [
                        'title' => "Set RoleSets",
                        'href' => "/dashboard/admin/setrolesets",
                    ],
                ],
            ]
        ];
    }
}
