<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Services\UpdateUserSetting2Service;
use Illuminate\Http\Request;
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
            return redirect()->route("dashboard.index");
        }

        return view("dashboards.dashboard-insp-matrix", [
            'controller' => $controller,
        ]);
    }
}
