{{-- @dd($items) --}}
@if(isset($items['children'][0]))
<div class='grid grid-cols-12 items-center'>
    @php
    $heading = $items['label'];
    $control = $items['control'];
    @endphp
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
        {{-- @default
        <x-feedback.alert type="warning" message="[{{$control}}] is not available" />
        @break --}}
        @endswitch
    </div>
</div>
<div class='grid grid-cols-12'>
    @foreach($items['children'] as $key => $item)
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
@endif
