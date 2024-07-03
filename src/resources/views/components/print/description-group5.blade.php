@if ($propTree['value'])
    <div class='grid grid-cols-12 items-center'>
        @php
        $prop = $propTree['value'];
        $heading = $prop['label'];
        $control = $prop['control'];
        @endphp
        <div class='col-span-12 text-left'>
            @switch($control)
            @case('z_page_break')
            {{-- <x-renderer.page-break /> --}}
            <div class="pagebreak"></div>
            @break
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
            @case('z_divider')
            <br/>
            @break
            @endswitch
        </div>
    </div>
    @if (isset($propTree['children']))
    <div class='grid grid-cols-12'>
            @foreach($propTree['children'] as $prop)
            @php
            $label = $prop['label'];
            $control = $prop['control'];
            $colSpan = $prop['col_span'];
            $columnName = $prop['column_name'];
            @endphp
                <x-print.description5 type={{$type}} modelPath={{$modelPath}} :prop="$prop" :dataSource="$dataSource" :item="$item" numberOfEmptyLines="{{$numberOfEmptyLines}}" printMode="{{$printMode}}"/>
            @endforeach
        </div>
    @endif
    
@else
    <div class='grid grid-cols-12'>
        @if ($propTree['children'])
            @foreach($propTree['children'] as $prop)
            <x-print.description5 type={{$type}} modelPath={{$modelPath}} :prop="$prop" :dataSource="$dataSource" :item="$item" numberOfEmptyLines="{{$numberOfEmptyLines}}" printMode="{{$printMode}}"/>
            @endforeach
        @else
            
        @endif
        
    </div>
@endif

