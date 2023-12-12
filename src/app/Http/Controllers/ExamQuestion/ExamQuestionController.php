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
        // dump($question);
        $numbers = substr($question, strlen("question_"));
        $numbers .= "__"; // make sure it always has [1] and [2]
        $numbers = explode("_", $numbers);
        $numbers = collect($numbers)->map(fn ($i) => $i ? $i : null)->toArray();
        return $numbers;
    }

    function parseDescription($descriptions)
    {
        // dump($descriptions);
        $result = [];
        foreach ($descriptions as $key => $value) {
            $numbers = substr($key, strlen("description_"));
            $numbers .= "__"; // make sure it always has [1] and [2]
            $numbers = explode("_", $numbers);
            $result[$numbers[0]][$numbers[1]] = $value;
        }

        // dump($result);
        return $result;
    }

    function update(Request $request, $type, $id)
    {
        // dump($request);
        $input = $request->except(['_token', '_method']);
        // dd($input);

        $input = collect($input);

        $questions = $input->filter(fn ($v, $k) => Str::startsWith($k, 'question_'));
        // dump($questions);

        $descriptions = $input->filter(fn ($v, $k) => Str::startsWith($k, 'description_'));
        $descriptions = $this->parseDescription($descriptions);
        // dump($descriptions);
        // dd();

        foreach ($questions as $question => $values) {
            [$questionId, $subQuestion1, $subQuestion2] = $this->parseQuestion($question);
            // dump($questionId, $subQuestion1, $subQuestion2);

            $answer =    [
                'owner_id' => CurrentUser::id(),
                // 'question_type_id' => $values,
                'response_ids' => is_array($values) ? join(",", $values) : $values,
            ];

            if ($subQuestion1) {
                $answer['sub_question_1_id'] = $subQuestion1;
                $answer['sub_question_1_value'] = $descriptions[$questionId][1];
            }
            if ($subQuestion2) {
                $answer['sub_question_2'] = $subQuestion2;
                $answer['sub_question_2_value'] = $descriptions[$questionId][2];
            }

            $result = Exam_sheet_line::updateOrCreate(
                ['exam_tmpl_id' => 1, 'exam_sheet_id' => 1, 'exam_question_id' => $questionId,],
                $answer,
            );
            // dump($result);
        }
        // dd();
        $types = Str::plural($type);
        return redirect(route($types . ".edit", $id));
    }
}
