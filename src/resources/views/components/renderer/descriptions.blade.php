@if(isset($items[0]))
<div class='grid grid-cols-12 items-center'>
    <div class='col-span-12 text-left'>
        @switch($control)
        @case('z_page_break')
        <x-renderer.page-break />
        @case('z_h1')
        <x-renderer.heading level=1>{{$heading}}</x-renderer.heading>
        @break
        @case('z_h2')
        <x-renderer.heading level=2>{{$heading}}</x-renderer.heading>
        @break
        @case('z_h3')
        <x-renderer.heading level=3>{{$heading}}</x-renderer.heading>
        @break
        @case('z_h4')
        <x-renderer.heading level=4>{{$heading}}</x-renderer.heading>
        @break
        @case('z_h5')
        <x-renderer.heading level=5>{{$heading}}</x-renderer.heading>
        @break
        @case('z_h6_base')
        <x-renderer.heading>{{$heading}}</x-renderer.heading>
        @break
        @default
        <x-feedback.alert type="warning" message="[{{$control}}] is not available" />
        @break
        @endswitch
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
    @php
    $x = $dataContent[$items['column_name']];
    $contents = $items['control'] === 'attachment' ? $x ->toArray() : $x;
    $colName = $items['column_name'];
    @endphp
    <x-renderer.show-item label="{{$items['label']}}" colName={{$colName}} colSpan="{{$items['col_span']}}" :contents="$contents" />
</div>
@endif
