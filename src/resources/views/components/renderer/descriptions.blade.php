{{-- @dd($items) --}}
@if(isset($prop['children'][0]))
<div class='grid grid-cols-12 items-center'>
    @php
    $heading = $prop['label'];
    $control = $prop['control'];
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
    @foreach($prop['children'] as $prp)
    @php
    $x = $items[$prp['column_name']];
    $contents = $prp['control'] === 'attachment' ? $x ->toArray() : $x;
    $label = $prp['label'];
    $colSpan = $prp['col_span'];
    $colName = $prp['column_name'];
    @endphp
    <x-renderer.show-item label={{$label}} colName={{$colName}} colSpan={{$colSpan}} :contents="$contents" />
    @endforeach
</div>
@endif
