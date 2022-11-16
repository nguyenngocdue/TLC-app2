<?php

namespace App\View\Components\Homepage;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Entities;
use Illuminate\Support\Str;

class SidebarEntityItems
{
    private static function maker($table, $svg)
    {
        $currentType = CurrentRoute::getTypeSingular();
        $singular = Str::singular($table);
        $isActive = ($currentType === $singular);
        return [
            "title" => Str::pretty($table),
            "type" => $singular,
            "icon" => $svg['form'],
            "isActive" => $isActive,
            "children" => [
                [
                    'title' => "View All",
                    'href' => route("{$table}_viewall.index"),
                    'isActive' => random_int(0, 1),
                ],
                [
                    'title' => "Add New",
                    'href' => route("{$table}_addnew.create"),
                    'isActive' => random_int(0, 1),
                ],
                [
                    'title' => "-",
                ],
                [
                    'title' => "Manage Props",
                    'href' => route("{$singular}_mngprop.index"),
                    'isActive' => random_int(0, 1),
                ],
                [
                    'title' => "Manage Relationships",
                    'href' => route("{$singular}_mngrls.index"),
                    'isActive' => random_int(0, 1),
                ],
                // [
                //     'title' => "Manage Tables",
                //     'href' => route("{$singular}_mnglnprop.index"),
                //     'isActive' => random_int(0, 1),
                // ],
            ],
        ];
    }

    public static function getAll($svg)
    {
        $tables = Entities::getAllTables();
        $items = array_map(fn ($table) => self::maker($table, $svg), $tables);
        return $items;
    }
}
