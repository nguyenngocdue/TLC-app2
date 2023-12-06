@php 
// $item = $dataSource[$index];
// dump($item);
$answerType = $item['answerType'] ?? null;
@endphp
<x-renderer.card title="Question 1:">
    {{$item['title']}} 
</x-renderer.card>
<x-renderer.card title="Answer 1:">
    @switch($answerType)
        @case('text')
        text
        @break
        @case('textarea')
        textarea
        @break
        @default
        Unknown {{$answerType}}
        @break
    @endswitch
</x-renderer.card>
            