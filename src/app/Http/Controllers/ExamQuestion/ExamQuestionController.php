<?php

namespace App\Http\Controllers\ExamQuestion;

use App\Models\Exam_sheet_line;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExamQuestionController
{
    function parseQuestion($question)
    {
        $numbers = substr($question, strlen("question_"));
        $numbers .= "__"; // make sure it always has [1] and [2]
        $numbers = explode("_", $numbers);
        $numbers = collect($numbers)->map(fn ($i) => $i ? $i : null)->toArray();
        return $numbers;
    }

    function update(Request $request, $type, $id)
    {
        // dump($request);
        $input = $request->except(['_token', '_method']);
        // dump($input);

        $input = collect($input);
        foreach ($input as $question => $values) {
            [$questionId, $subQuestion1, $subQuestion2] = $this->parseQuestion($question);
            // dump($questionId, $subQuestion1, $subQuestion2);

            $answer =    [
                'owner_id' => CurrentUser::id(),
                // 'question_type_id' => $values,
                'response_ids' => is_array($values) ? join(",", $values) : $values,
            ];

            if ($subQuestion1) $answer['sub_question_1'] = $subQuestion1;
            if ($subQuestion2) $answer['sub_question_2'] = $subQuestion2;

            $result = Exam_sheet_line::updateOrCreate(
                ['exam_tmpl_id' => 1, 'exam_sheet_id' => 1, 'exam_question_id' => $questionId,],
                $answer,
            );
            // dump($result);
        }

        $types = Str::plural($type);
        return redirect(route($types . ".edit", $id));
    }
}
