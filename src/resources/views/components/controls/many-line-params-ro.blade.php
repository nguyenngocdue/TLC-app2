<x-renderer.table 
        tableName="{{$table01ROName}}" 
        :columns="$readOnlyColumns" 
        :dataSource="$dataSource" 
        howNo="{{$showNo?1:0}}" 
        footer="{{$tableFooter}}" 
        noCss="{{$noCss}}" 
        numberOfEmptyLines="{{$numberOfEmptyLines}}"
        showNo={{$noCss}}
        />

@if(!$readOnly && $createANewForm)
    <x-renderer.button type='success' href='{!!$href!!}' >Create a new {{$nickname}}</x-renderer.button>
    
    {{-- @dump($tableSettings) --}}

    {{-- @if( isset($tableSettings['button_recalculate']) && $tableSettings['button_recalculate']) --}}
        {{-- <x-renderer.button class="hidden1" disabled="{{$readOnly}}" type="secondary" onClick="refreshCalculation('{{$table01Name}}')"><i class="fa-solid fa-calculator"></i> Recalculate</x-renderer.button> --}}
        <script>
            $(document).ready(()=>{
                console.log("refreshCalculationRO")
                refreshCalculationRO('{{$table01Name}}')
            })
        </script>
    {{-- @endif --}}

@endif