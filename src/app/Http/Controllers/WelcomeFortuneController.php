<?php

namespace App\Http\Controllers;

use App\Models\Sub_project;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $dataSource = Sub_project::query()
            ->with("getProject")
            ->whereIn('status', ['manufacturing', 'construction'])
            ->get();
        // dump($dataSource[0]);
        $columns = [];
        $itemIds = [];
        foreach ($dataSource as $item) {
            $id = ($project = $item->getProject) ? $project->id : 0;
            $name = ($project = $item->getProject) ? $project->name : "orphan";
            $columns[$id]['name'] = $name;
            $columns[$id]['items'][] = $item;
            // $itemIds[] = $item->id;
        }
        // dump($columns);
        // dump($columns[1]['items']);
        return view("welcome-fortune", [
            // 'dataSources' => $dataSource,
            'columns' => $columns,
            // 'itemIds' => $itemIds,
        ]);
    }
}
