<?php

namespace App\Http\Controllers;

use App\Http\Services\WorkingShiftService;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\User;
use App\Models\Workplace;
use App\Notifications\SampleNotification;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class WelcomeFortuneController extends Controller
{
    function __construct(
        private WorkingShiftService $wss
    ) {
    }
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        if (!CurrentUser::isAdmin()) return abort("Nothing here", 404);

        $dataSource = [
            [
                'title' => "Welcome",
                'href' => 'http://localhost',
                'answerType' => 'checkbox',
                'children' => [
                    [
                        'title' => "Welcome",
                        'href' => 'http://localhost',
                        'children' => [
                            [
                                'title' => "Welcome",
                                'href' => 'http://localhost',
                                'children' => [],
                            ],
                            [
                                'title' => "Goodbye",
                                'href' => 'http://localhost',
                            ],
                        ],
                    ],
                    [
                        'title' => "Goodbye",
                        'href' => 'http://localhost',
                    ],
                ],
            ],
            [
                'title' => "Goodbye",
                'href' => 'http://localhost',
            ],
        ];
        return view("welcome-fortune", ['dataSource' => $dataSource,]);
    }
}
