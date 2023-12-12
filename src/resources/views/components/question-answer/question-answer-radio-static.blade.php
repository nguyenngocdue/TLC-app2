{{-- @dump($staticAnswer) --}}
{{-- @dump($questionId) --}}
{{-- @dump($renderAsRows) --}}

@php
    $value = ($line) ? $line[0][0]->response_ids : "";
    $questionJSKey = "{$questionId}";
@endphp
<script>questions['{{$questionJSKey}}']='{{$value}}';</script>

<x-question-answer.question-answer-badge id="{{$questionJSKey}}" selected="{{$value}}" validation="{{$validation}}"/>
<div class="grid {{$renderAsRows ?: 'grid-cols-12'}}">
    @foreach($staticAnswer as $label)
    <div class="{{$renderAsRows ?: 'text-center'}} col-span-1 m-1 p-2 rounded hover:bg-blue-100" onclick="">
        <input class="cursor-pointer" type="radio" 
                @checked($label == $value) 
                id="option_{{$questionId}}_{{$label}}" 
                name="question_{{$questionId}}"
                onchange="refreshValidation('{{$questionJSKey}}', '{{$validation}}', event.target.value)" 
                value="{{$label}}:::{{$label}}">
        @if(!$renderAsRows) <br/> @endif
        <label class="cursor-pointer" for="option_{{$questionId}}_{{$label}}">{{$label}}</label><br>
    </div>
    @endforeach
</div>
