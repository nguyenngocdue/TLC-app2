<x-renderer.card title=" {{$item['name']}} ">
    {{$item['description']}} 
    <hr/>
    <x-question-answer.question-answer-{{$control}} 
        staticAnswer="{{$staticAnswer}}" 
        dynamicAnswer="{{$dynamicAnswer}}"
        />
</x-renderer.card>
            