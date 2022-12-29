<?php

namespace App\View\Components\Homepage;

use App\Utils\Support\CurrentRoute;

class SidebarAdminItems
{
    private static function getPermission($currentType, $svg)
    {
        $isActive = ($currentType === 'permission');
        return [
            "title" => "Permissions",
            // "type" => "ppppp",
            "icon" => $svg['modals'],
            "isActive" => $isActive,
            "children" => [
                [
                    'title' => "Permissions",
                    'href' => "/dashboard/admin/permissions",
                ],
                [
                    'title' => "Permissions 2",
                    'href' => "/dashboard/admin/permissions2",
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
        ];
    }

    private static function getWorkflow($currentType, $svg)
    {
        $isActive = ($currentType === 'workflow');
        return [
            "title" => "Workflows",
            // "type" => "ppppp",
            "icon" => $svg['layout'],
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
            ],
        ];
    }

    public static function getAll($svg)
    {
        $currentType = CurrentRoute::getTypeSingular();
        $result[] = self::getPermission($currentType, $svg);
        $result[] = self::getWorkflow($currentType, $svg);
        return $result;
    }
}
