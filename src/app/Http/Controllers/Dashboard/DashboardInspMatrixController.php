<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Services\UpdateUserSetting2Service;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class DashboardInspMatrixController extends Controller
{
    public function index(Request $request, $controller)
    {
        $key = $request->get('key');
        $page = $request->get('page');

        if ($key && $page) {
            UpdateUserSetting2Service::getInstance()
                ->set("qaqc_insp_chklst_shts.view_all.matrix.$key.page", $page)
                ->commit();

            $query = $request->query();
            Arr::pull($query, 'page');
            Arr::pull($query, 'key');
            // $query = http_build_query($query);
            return redirect()->route("dashboard.index", $query);
        }

        $tab = $request->get('tab', 'sqbts');
        $tabs = [];

        $cu = CurrentUser::get();

        if ($cu->isProjectClient() || CurrentUser::isAdmin()) {
            $tabs[] = ['title' => 'Production Progress', 'active' => $tab == 'sqbts', 'href' => "?tab=sqbts"];
        }
        $tabs[] = ['title' => 'QAQC Progress', 'active' => $tab == 'ics', 'href' => "?tab=ics"];
        $tabs[] = ['title' => 'Sign-Off Request', 'active' => $tab == 'sign-off', 'href' => "?tab=sign-off"];

        return view("dashboards.dashboard-insp-matrix", [
            'controller' => $controller,
            'tabs' => $tabs,
            'tab' => $tab,
        ]);
    }
}
