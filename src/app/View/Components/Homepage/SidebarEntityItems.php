<?php

namespace App\View\Components\Homepage;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Entities;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SidebarEntityItems
{
    private static function maker($table, $svg)
    {
        $currentType = CurrentRoute::getTypeSingular();
        $singular = Str::singular($table);
        $isActive = ($currentType === $singular);

        $modelPath = "App\\Models\\" . Str::ucfirst($singular);
        $model = App::make($modelPath);

        $result = [
            "title" => $model->menuTitle ?? Str::headline($table),
            "type" => $singular,
            "icon" => $svg['form'],
            "isActive" => $isActive,
            "children" => [
                [
                    'title' => "View All",
                    'href' => route("{$table}.index"),
                ],
                [
                    'title' => "Add New",
                    'href' => route("{$table}.create"),
                ],
                ['title' => "-",],
                [
                    'title' => "Manage Props",
                    'href' => route("{$singular}_mngprop.index"),
                ],
                [
                    'title' => "Manage Relationships",
                    'href' => route("{$singular}_mngrls.index"),
                ],
                // ['title' => '-'],
                // [
                //     'title' => "Manage Statuses",
                //     'href' => route("{$singular}_mngstt.index"),
                // ],
            ],
        ];

        if (method_exists($model, "transitionTo")) {
            $result['children'][] =  ['title' => '-'];
            $result['children'][] =  [
                'title' => "Manage Statuses",
                'href' => route("{$singular}_mngstt.index"),
            ];
        }

        return $result;
    }

    public static function getAll($svg)
    {
        $tables = Entities::getAllPluralNames();
        $items = array_map(fn ($table) => self::maker($table, $svg), $tables);
        return $items;
    }
}
