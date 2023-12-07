<x-renderer.card title="{{$item['name']}}">
    {{$item['description']}} 
    <hr/>
    @php
        echo Blade::render('<x-question-answer.question-answer-'.$control.'
        :questionId="$questionId" 
        :staticAnswer="$staticAnswer" 
        :dynamicAnswer="$dynamicAnswer" 
        :renderAsRows="$renderAsRows"
        />', [
            'questionId' => $item['id'],
            'staticAnswer' => $staticAnswer,
            'dynamicAnswer' => $dynamicAnswer,
            'renderAsRows' => $renderAsRows,
        ]);
    @endphp
 
</x-renderer.card>
            