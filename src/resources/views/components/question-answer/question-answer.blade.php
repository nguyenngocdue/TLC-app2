@php 
$item = $dataSource[$index];
dump($item);
@endphp
<x-renderer.card title="Question 1:">
    {{$item['title']}}
</x-renderer.card>
<x-renderer.card title="Answer 1:">
    {{$item['answerType'] ?? 'null'}}
</x-renderer.card>
            