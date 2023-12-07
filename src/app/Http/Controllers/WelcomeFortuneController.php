<?php

namespace App\Http\Controllers;

use App\Http\Services\WorkingShiftService;
use App\Models\Exam_tmpl_question;
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

        $id = $request->id ?? 1;
        $dataSource = Exam_tmpl_question::query()
            ->where("exam_tmpl_id", $id)
            ->get();

        // dump($dataSource);

        return view("welcome-fortune", [
            'dataSource' => $dataSource,
            'isOnePage' => true,
        ]);
    }
}
