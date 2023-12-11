<?php

namespace App\View\Components\Controls\ExamSheet;

use App\Models\Exam_tmpl_question;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class ExamSheetPage extends Component
{
    function __construct(
        private $id,
        private $type,
    ) {
    }

    function render()
    {
        $id = $this->id ?? 1;
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
        $route = route(Str::plural($this->type) . '.update', $this->id);

        return view('components.controls.exam-sheet.exam-sheet-page', [
            'dataSource' => $dataSource,
            'tableOfContents' => $tableOfContents,
            'isOnePage' => true,
            'route' => $route,
        ]);
    }
}
