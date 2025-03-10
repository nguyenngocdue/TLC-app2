<?php

namespace App\View\Components\Homepage;

use App\Utils\Support\CurrentRoute;

class SidebarAdminItems
{
    private static function getPermission($currentType)
    {
        $isActive = ($currentType === 'permission');
        return [
            "title" => "Permissions",
            // "type" => "ppppp",
            "icon" => "fa-duotone fa-dice-d20",
            "isActive" => $isActive,
            "children" => [
                [
                    'title' => "Permissions",
                    'href' => "/dashboard/admin/permissions",
                ],
                // [
                //     'title' => "Permissions 2",
                //     'href' => "/dashboard/admin/permissions2",
                // ],
                [
                    'title' => "Roles",
                    'href' =>  "/dashboard/admin/roles",
                ],
                [
                    'title' => "RoleSets",
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
                    'title' => "Set Roles to RoleSets",
                    'href' => "/dashboard/admin/setroles",
                ],
                [
                    'title' => "Set RoleSets to Users",
                    'href' => "/dashboard/admin/setrolesets",
                ],
            ],
        ];
    }

    private static function getWorkflow($currentType)
    {
        $isActive = ($currentType === 'workflow');
        return [
            "title" => "Workflows",
            // "type" => "ppppp",
            "icon" => "fa-duotone fa-bars-staggered",
            "isActive" => $isActive,
            "children" => [
                [
                    'title' => "Status Library",
                    'href' => "/dashboard/workflow/manageStatuses",
                ],
                [
                    'title' => "Widget Library",
                    'href' => "/dashboard/workflow/manageWidgets",
                ],
                [
                    'title' => "App Library",
                    'href' => "/dashboard/workflow/manageApps",
                ],
            ],
        ];
    }

    public static function getAll()
    {
        $currentType = CurrentRoute::getTypeSingular();
        $result[] = self::getPermission($currentType);
        $result[] = self::getWorkflow($currentType);
        return $result;
    }
}
