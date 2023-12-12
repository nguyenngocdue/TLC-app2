@php
    $value = ($line) ? $line[0][0]->response_ids : "";
@endphp
<textarea name="question_{{$questionId}}" class="border rounded p-1 m-1 w-full border-gray-500" rows="4">{!! $value !!}</textarea>