<?php

namespace App\View\Components\Controls\ExamSheet;

use App\Models\Exam_sheet;
use App\Models\Exam_tmpl_question;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class ExamSheetPageEdit extends Component
{
    function __construct(
        private $id,
        private $type,
    ) {
        // dump($id, $type);
    }

    function groupByQuestionId($lines)
    {
        $grouped = [];
        foreach ($lines as $line) {
            $grouped[$line->exam_question_id][$line->sub_question_1_id ?? 0][$line->sub_question_2_id ?? 0] = $line;
        }
        // dd($grouped);
        return $grouped;
    }

    function render()
    {
        $id = $this->id;
        $sheet = Exam_sheet::query()
            ->where('id', $id)
            ->with('getSheetLines')
            ->get();
        $sheet = $sheet[0];
        // dump($sheet);

        $exam_tmpl_id = $sheet->exam_tmpl_id;
        $dataSource = Exam_tmpl_question::query()
            ->where("exam_tmpl_id", $exam_tmpl_id)
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

        $sheetLines = $this->groupByQuestionId($sheet->getSheetLines);
        $sheetStatus = $sheet->status;
        if ($sheetStatus == 'finished') {
            return view('components.controls.exam-sheet.exam-sheet-page-finished', [
                'sheet' => $sheet,
                'cuid' => CurrentUser::id(),
            ]);
        }

        if ($sheetStatus == 'submitted') {
            return view('components.controls.exam-sheet.exam-sheet-page-submitted', [
                'route' => $route,
                'exam_tmpl_id' => $exam_tmpl_id,
                'exam_sheet_id' => $id,
                'status' => $sheetStatus,
            ]);
        }

        return view('components.controls.exam-sheet.exam-sheet-page-edit', [
            'dataSource' => $dataSource,
            'tableOfContents' => $tableOfContents,
            'isOnePage' => true,
            'route' => $route,

            'sheetLines' => $sheetLines,
            'exam_sheet_id' => $id,
            'exam_tmpl_id' => $exam_tmpl_id,
            'status' => $sheetStatus,
        ]);
    }
}
