@php
    $value = ($line) ? $line[0][0]->response_ids : "";
    $questionJSKey = $questionId;
@endphp
{{-- <script>questions['{{$questionJSKey}}']='{{$value}}';</script> --}}
<x-question-answer.question-answer-badge id="{{$questionJSKey}}" selected="{{$value}}" validation="{{$validation}}"/>
<textarea name="question_{{$questionId}}" 
        @readonly($readOnly)
        onchange="refreshValidation('{{$questionJSKey}}', '{{$validation}}', event.target.value)" 
        class="border rounded p-1 m-1 w-full border-gray-500" 
        rows="4">{!! $value !!}</textarea>