<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $statuses = LibStatuses::getFor('hr_overtime_request');
        $dataSource = [
            'all' => ['href' => '', 'title' => 'All']
        ];
        foreach ($statuses as $statusKey => $status) {
            $dataSource[$statusKey] = [
                'href' => '#',
                'title' => "<x-renderer.status>" . $statusKey . "</x-renderer.status>",
            ];
        }

        return view("welcome-fortune", [
            'dataSource' => $dataSource,
        ]);
    }
}
