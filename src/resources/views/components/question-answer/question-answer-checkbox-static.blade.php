{{-- @dump($staticAnswer) --}}
{{-- @dump($questionId) --}}
{{-- @dump($renderAsRows) --}}

@php
    $value = ($line) ? $line[0][0]->response_values : "";
    // dump($value);
    $values = $value ? explode("|||", $value) : [];
    // dump($values);
@endphp

<x-question-answer.question-answer-badge id="{{$questionJSKey}}" selected="{{$value}}" validation="{{$validation}}"/>
<div class="grid {{$renderAsRows ?: 'grid-cols-12'}}">
    @foreach($staticAnswer as $index => $label)
    <div class="{{$renderAsRows ?: 'text-center'}} col-span-1 m-1 p-2 rounded hover:bg-blue-100" onclick="">
        <input class="cursor-pointer" type="checkbox" 
        id="option_{{$questionId}}_{{$label}}" 
        @checked(in_array($label, $values))
        name="question_{{$questionId}}[]" 
        value="{{$index}}:::{{$label}}">
        @if(!$renderAsRows) <br/> @endif
        <label class="cursor-pointer" for="option_{{$questionId}}_{{$label}}">{{$label}}</label><br>
    </div>
    @endforeach
</div>
