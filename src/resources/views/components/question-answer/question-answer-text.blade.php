@php
    $value = ($line) ? $line[0][0]->response_ids : "";
    // dump($value);
    $questionJSKey = $questionId;
@endphp
{{-- <script>questions['{{$questionJSKey}}']='{{$value}}';</script> --}}
<x-question-answer.question-answer-badge id="{{$questionJSKey}}" selected="{{$value}}" validation="{{$validation}}"/>
<input name="question_{{$questionId}}" 
    class="border rounded p-1 m-1 w-full border-gray-500" 
    value="{{$value}}"
    @readonly($readOnly)
    onchange="refreshValidation('{{$questionJSKey}}', '{{$validation}}', event.target.value)" 
    />