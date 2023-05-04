<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Qaqc_ncr;
use App\Models\Qaqc_wir;
use App\Models\User;
use App\Models\User_team_ot;
use App\Models\Zunit_test_06;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\Json\SuperWorkflows;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        //https://laravel-news.com/laravel-5-8-22
        $items = Qaqc_ncr::query()
            ->whereIn('id', [7, 8])
            ->with([
                'getParent' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        Qaqc_wir::class => ['getNcrs'],
                    ]);
                },

            ])
            ->limit(10)
            ->get();
        dump($items);
        $items = Comment::query()
            ->with([
                'commentable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        Zunit_test_06::class => ['comment_3'],
                    ]);
                },

            ])
            ->limit(10)
            ->get();
        dump($items);
        // return view("welcome-fortune", []);
    }
}
