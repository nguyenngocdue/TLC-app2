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
        $id = $request->id ?? 1;
        $dataSource = Exam_tmpl_question::query()
            ->where("exam_tmpl_id", $id)
            ->with('getExamTmplGroup')
            ->orderBy('order_no')
            ->get();
        // dump($dataSource);

        $isAManager =  CurrentUser::get()->isAManager();
        // $isAManager = true;
        $HIDE_WHEN_I_AM_NOT_A_MANAGER = 400;

        $dataSource = $dataSource->filter(fn ($i) => ($i->getExamTmplGroup->hide_when != $HIDE_WHEN_I_AM_NOT_A_MANAGER || $isAManager));

        $tableOfContents = $dataSource->map(fn ($i) => $i->getExamTmplGroup)->unique();
        // dump($tableOfContents);
        $route = route('exam-question.update', 1);

        return view("welcome-fortune", [
            'dataSource' => $dataSource,
            'tableOfContents' => $tableOfContents,
            'isOnePage' => true,
            'route' => $route,
        ]);
    }
}
