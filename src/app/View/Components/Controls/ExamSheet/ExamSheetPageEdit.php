<?php

namespace App\View\Components\Controls\ExamSheet;

use App\Models\Exam_sheet;
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

    function render()
    {
        $id = $this->id;
        $sheet = Exam_sheet::query()
            ->where('id', $id)
            ->with('getSheetLines')
            ->get();
        $sheet = $sheet[0];
        $exam_tmpl_id = $sheet->exam_tmpl_id;
        // dump($sheet);

        $dataSource = Exam_sheet::getQuestionsOfSheet($sheet);
        $tableOfContents = $dataSource->map(fn ($i) => $i->getExamTmplGroup)->unique();
        // dump($tableOfContents);
        $route = route(Str::plural($this->type) . '.update', $this->id);

        $sheetLines = Exam_sheet::groupByQuestionId($sheet->getSheetLines);
        $sheetStatus = $sheet->status;

        if ($sheet->owner_id !== CurrentUser::id()) {
            return Blade::render('<x-feedback.result type="error" title="Access denied" message="You can\'t view other\'s exam sheet." />');
        }

        if ($sheetStatus == 'finished') {
            return view('components.controls.exam-sheet.exam-sheet-page-finished', [
                'route' => route('exam_sheets.show', $id),
                // 'exam_sheet_id' => $id,
                // 'exam_tmpl_id' => $exam_tmpl_id,
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
