<?php

namespace App\View\Components\Controls\ExamSheet;

use App\Models\Exam_tmpl;
use App\Models\Exam_tmpl_question;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class ExamSheetPageCreate extends Component
{
    function __construct(
        private $type,
    ) {
        // dump($type);
    }

    function render()
    {
        $isAdmin = CurrentUser::isAdmin();
        $availableExams = Exam_tmpl::query();
        if (!$isAdmin) $availableExams = $availableExams->where('id', 2);
        $availableExams = $availableExams->get();
        // dump($availableExams);
        // 
        $route = route(Str::plural($this->type) . '.store');

        return view('components.controls.exam-sheet.exam-sheet-page-create', [
            // 'dataSource' => $dataSource,
            // 'tableOfContents' => $tableOfContents,
            'availableExams' => $availableExams,
            'isOnePage' => true,
            'route' => $route,
            'cuid' => CurrentUser::id(),
        ]);
    }
}
