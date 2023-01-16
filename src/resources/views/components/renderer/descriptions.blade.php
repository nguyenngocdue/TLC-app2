{{-- @dump($items) --}}
@if(isset($items[0]))
<div class='grid grid-cols-12 items-center'>
    <div class='col-span-12 text-left'>
        <x-renderer.heading level=1>{{$heading}}</x-renderer.heading>
    </div>
</div>
<div class='grid grid-cols-12'>
    @foreach($items as $key => $item)
    @php
    $x = $dataContent[$item['column_name']];
    $contents = $item['control'] === 'attachment' ? $x ->toArray() : $x;
    $label = $item['label'];
    $colSpan = $item['col_span'];
    $colName = $item['column_name'];
    @endphp

    <x-renderer.show-item label={{$label}} colName={{$colName}} colSpan={{$colSpan}} :contents="$contents" />
    @endforeach
</div>
@else
<div class='grid grid-cols-12 border'>
    {{-- <x-renderer.show-item label="{{$items['label']}}" colSpan="{{$items['col_span']}}" :content="$content" /> --}}
</div>
@endif
