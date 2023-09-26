<?php

namespace App\Http\Controllers;

use App\Models\Kanban_task;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    private $className = Kanban_task::class;
    private $category = 'kanban_group_id';

    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $table = Kanban_task::getTableName();
        if (!CurrentUser::isAdmin()) return abort("Nothing here", 404);
        $dataSource = $this->className::query()
            ->with("getParent")
            ->get();
        // dump($dataSource[0]);
        $columns = [];
        foreach ($dataSource as $item) {
            $id = ($project = $item->getParent) ? $project->id : 0;
            $name = ($project = $item->getParent) ? $project->name : "orphan";
            $columns[$id]['name'] = $name;
            $columns[$id]['items'][] = $item;
            // $itemIds[] = $item->id;
        }
        $route = route($table . ".kanban");
        return view("welcome-fortune", [
            'columns' => $columns,
            'category' => $this->category,
            'route' => $route,
        ]);
    }
}
