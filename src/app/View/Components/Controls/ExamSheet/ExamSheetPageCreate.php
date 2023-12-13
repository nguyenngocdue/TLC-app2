<?php

namespace App\View\Components\Controls\ExamSheet;

use App\Models\Exam_sheet;
use App\Models\Exam_tmpl;
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
        $cuid = CurrentUser::id();
        $isAdmin = CurrentUser::isAdmin();

        $sheets = Exam_sheet::query()->where('owner_id', $cuid)->get();

        $allExams = Exam_tmpl::query()->whereNotIn('id', $sheets->pluck('exam_tmpl_id'));
        if (!$isAdmin) $allExams = $allExams->where('id', 2);
        $allExams = $allExams->get();
        // dump($availableExams);

        // dump($sheets);

        $routeStore = route(Str::plural($this->type) . '.store');


        return view('components.controls.exam-sheet.exam-sheet-page-create', [
            // 'dataSource' => $dataSource,
            // 'tableOfContents' => $tableOfContents,
            'type' => $this->type,
            'availableExams' => $allExams,
            'myInProgressSheets' => $sheets->filter(fn ($i) => $i->status == 'in_progress'),
            'mySubmittedSheets' => $sheets->filter(fn ($i) => $i->status == 'submitted'),
            'myFinishedSheets' => $sheets->filter(fn ($i) => $i->status == 'finished'),
            'isOnePage' => true,
            'routeStore' => $routeStore,
            'cuid' => $cuid,
        ]);
    }
}
