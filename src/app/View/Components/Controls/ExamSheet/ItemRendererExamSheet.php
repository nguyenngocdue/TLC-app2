<?php

namespace App\View\Components\Controls\ExamSheet;

use App\Models\Exam_tmpl_question;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class ItemRendererExamSheet extends Component
{
    function __construct(
        private $id,
        private $item,
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
        // $route = route('exam-question.update', $this->id);

        return view('components.controls.exam-sheet.item-renderer-exam-sheet', [
            'dataSource' => $dataSource,
            'tableOfContents' => $tableOfContents,
            'isOnePage' => true,
            // 'route' => $route,
        ]);
    }
}
