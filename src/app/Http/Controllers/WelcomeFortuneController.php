<?php

namespace App\Http\Controllers;

use App\Http\Services\WorkingShiftService;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

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
                'title' => "General",
                'answerType' => 'checkbox',
                'children' => [
                    [
                        'title' => "Rate your satisfaction",
                        'answerType' => 'checkbox',
                    ],
                    [
                        'title' => "What could we improve",
                        'answerType' => 'checkbox',

                    ],
                ],
            ],
            [
                'title' => "Rate your manager",
                'children' => [
                    [
                        'title' => "I get clear",
                        'answerType' => 'checkbox',
                    ],
                    [
                        'title' => "My manager is",
                        'answerType' => 'checkbox',

                    ],
                ],
            ],
            [
                'title' => "Self Assessment",
                'children' => [
                    [
                        'title' => "I know how",
                        'answerType' => 'checkbox',
                    ],
                    [
                        'title' => "I communicate with",
                        'answerType' => 'checkbox',

                    ],
                ],
            ],
            [
                'title' => "Manager Assessment",
                'children' => [
                    [
                        'title' => "This person know",
                        'answerType' => 'checkbox',
                    ],
                    [
                        'title' => "This person communicate",
                        'answerType' => 'checkbox',

                    ],
                ],
            ],
            [
                'title' => "Your team effectiveness",
                'children' => [
                    [
                        'title' => "My team meet",
                        'answerType' => 'checkbox',
                    ],
                    [
                        'title' => "Rank your team",
                        'answerType' => 'checkbox',

                    ],
                ],
            ],
            [
                'title' => "Your peer assessment",
                'children' => [
                    [
                        'title' => "Participation in",
                        'answerType' => 'checkbox',
                    ],
                    [
                        'title' => "Willingness",
                        'answerType' => 'checkbox',

                    ],
                ],
            ],
            [
                'title' => "Related Department Assessment",
                'children' => [
                    [
                        'title' => "This department displays",
                        'answerType' => 'checkbox',
                    ],
                    [
                        'title' => "This department has excel",
                        'answerType' => 'checkbox',

                    ],
                ],
            ],
        ];
        return view("welcome-fortune", [
            'dataSource' => $dataSource,
            'isOnePage' => true,
        ]);
    }
}
