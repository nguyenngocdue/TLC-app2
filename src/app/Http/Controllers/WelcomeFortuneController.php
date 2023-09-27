<?php

namespace App\Http\Controllers;

use App\Models\Kanban_task;
use App\Models\Kanban_task_cluster;
use App\Models\Kanban_task_group;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    private $clusterClass = Kanban_task_cluster::class;
    private $category_group = 'kanban_group_id';
    private $category_cluster = 'kanban_cluster_id';
    private $debug = false;

    public function getType()
    {
        return "dashboard";
    }

    private function getDataSourceClusters()
    {
        return $this->clusterClass::query()
            ->with("getGroups.getTasks")
            // ->with(["getGroups" => function ($query) {
            //     $query->whereHas('getTasks', function ($query) {
            //     });
            // }])
            ->get();
    }

    public function index(Request $request)
    {
        if (!CurrentUser::isAdmin()) return abort("Nothing here", 404);
        $clusters = $this->getDataSourceClusters();
        $route_task = route(Kanban_task::getTableName() . ".kanban");
        $route_group = route(Kanban_task_group::getTableName() . ".kanban");
        $route_cluster = route(Kanban_task_cluster::getTableName() . ".kanban");

        return view("welcome-fortune", [
            // 'debug' => $debug,
            'hidden' => $this->debug ? "" : "hidden",
            'clusters' => $clusters,

            'route_task' => $route_task,

            'route_group' => $route_group,
            'category_group' => $this->category_group,

            'route_cluster' => $route_cluster,
            'category_cluster' => $this->category_cluster,
        ]);
    }
}
