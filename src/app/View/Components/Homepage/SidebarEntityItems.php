<?php

namespace App\View\Components\Homepage;

use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Entities;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SidebarEntityItems
{
    private static function maker($table)
    {
        $currentType = CurrentRoute::getTypeSingular();
        $singular = Str::singular($table);
        $isActive = ($currentType === $singular);

        $modelPath = Str::modelPathFrom($singular);
        $model = App::make($modelPath);

        $result = [
            "title" => $model->getMenuTitle(),
            "type" => $singular,
            "icon" => $isActive ? "fa-duotone fa-folder-open" : "fa-duotone fa-folder",
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
                    'title' => "Manage Workflows",
                    'href' => route("{$singular}_prp.index"),
                ],
            ],
        ];

        if (method_exists($model, "getProperties")) {
            $result['children'][] =  ['title' => "Manage Properties", 'href' => route("{$singular}_ppt.index"),];
        }

        return $result;
    }

    public static function getAll()
    {
        $tables = Entities::getAllPluralNames();
        $items = array_map(fn ($table) => self::maker($table), $tables);
        return $items;
    }
}
