<?php

namespace App\Http\Controllers\ExamQuestion;

use App\Models\Exam_sheet_line;
use App\Utils\Support\CurrentUser;
use App\View\Components\QuestionAnswer\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExamQuestionController
{
    function store(Request $request, $type)
    {
        $input = $request->input();
        // dump($type);
        $modelPath = Str::modelPathFrom($type);
        // dump($modelPath);
        $item = $modelPath::create($input);
        // dump($id);
        $plural = Str::plural($type);
        $route = route($plural . ".edit", $item->id);
        return redirect($route);
    }

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

        // dd($result);
        return $result;
    }

    function parseControls($controls)
    {
        $result = [];
        foreach ($controls as $k => $control) {
            $result[substr($k, strlen('control_'))] = $control;
        }
        return $result;
    }

    function update(Request $request, $type, $id)
    {
        $controlIds = QuestionAnswer::controlIds();
        // dump($request);
        $input = $request->input();
        $exam_tmpl_id = $input['exam_tmpl_id'];
        $exam_sheet_id = $input['exam_sheet_id'];
        // dd($input);

        $input = collect($input);

        $questions = $input->filter(fn ($v, $k) => Str::startsWith($k, 'question_'));
        // dump($questions);
        $controls = $input->filter(fn ($v, $k) => Str::startsWith($k, 'control_'));
        $controls = $this->parseControls($controls);
        $descriptions = $input->filter(fn ($v, $k) => Str::startsWith($k, 'description_'));
        $descriptions = $this->parseDescription($descriptions);
        // dump($descriptions);
        // dd();

        foreach ($questions as $question => $values) {
            [$questionId, $subQuestion1, $subQuestion2] = $this->parseQuestion($question);
            $questionTypeId = $controls[$questionId];
            // dump($questionId, $subQuestion1, $subQuestion2);

            $answer =    [
                'owner_id' => CurrentUser::id(),
                'question_type_id' => $questionTypeId,
            ];

            if (in_array($controlIds[$questionTypeId], ['text', 'textarea'])) {
                $answer['response_ids'] = $values;
                $answer['response_values'] = $values;
            } else {
                if (is_array($values)) {
                    $keys = [];
                    $strings = [];
                    foreach ($values as $value) {
                        [$key, $value] = explode(":::", $value);
                        $keys[] = $key;
                        $strings[] = $value;
                    }
                    $answer['response_ids'] = join(",", $keys);
                    $answer['response_values'] = join(",", $strings);
                } else {
                    [$ids, $values] = explode(":::", $values);
                    $answer['response_ids'] = $ids;
                    $answer['response_values'] = $values;
                }
            }

            $uniqueArray = [
                'exam_tmpl_id' => $exam_tmpl_id,
                'exam_sheet_id' => $exam_sheet_id,
                'exam_question_id' => $questionId,
            ];

            if ($subQuestion1) {
                $uniqueArray['sub_question_1_id'] = $subQuestion1;
                $answer['sub_question_1_value'] = $descriptions[$questionId][$subQuestion1];
            }
            if ($subQuestion2) {
                $uniqueArray['sub_question_2_id'] = $subQuestion2;
                $answer['sub_question_2_value'] = $descriptions[$questionId][$subQuestion2];
            }

            $result = Exam_sheet_line::updateOrCreate(
                $uniqueArray,
                $answer,
            );
            // dump($result);
            // dump($uniqueArray);
            // dump($answer);
        }
        // dd();
        $types = Str::plural($type);
        return redirect(route($types . ".edit", $id));
    }
}
