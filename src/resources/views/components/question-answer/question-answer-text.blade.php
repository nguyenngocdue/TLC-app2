@php
    $value = ($line) ? $line[0][0]->response_ids : "";
    // dump($value);
@endphp
<x-question-answer.question-answer-badge id="{{$questionJSKey}}" selected="{{$value}}"/>
<input name="question_{{$questionId}}" class="border rounded p-1 m-1 w-full border-gray-500" value="{{$value}}"/>