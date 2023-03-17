@if($readOnly)
    @if($multiple)
        Not implemented yet
    @else 
        @php 
        $selectedArray = json_decode($selected); 
        $modelPath = Str::modelPathFrom($table);
        $nameless = (new $modelPath)->nameless;
        
        $label = $nameless ? "Nameless #$selected" : DB::table($table)->whereIn('id',$selectedArray)->pluck('name');
        $selected = (empty($selectedArray)) ? null : $selectedArray[0]; 
        @endphp
        <input id='{{$id}}' name='{{$name}}' class='{{$classList}}' value="{{$selected}}" type="hidden"/>
        <div class="px-2">{{$label[0]??""}}</div>
    @endif
@else
    <select id='{{$id}}' name='{{$name}}' {{$multiple ? "multiple" : ""}} class='{{$classList}}'
    {{-- onChange='onChangeDropdown4({name:"{{$name}}", lineType:"{{$lineType}}", table01Name:"{{$table01Name}}", rowIndex:{{$rowIndex}}, saveOnChange:{{$saveOnChange?1:0}}})' --}}
    ></select>
    <script>
        $(document).ready(()=>{
            const params = {id:'{{$id}}', table01Name: '{{$table01Name}}', selectedJson: '{!! $selected !!}', table: '{{$table}}'}
            documentReadyDropdown4(params)
        })
    </script>
@endif

<script>
    $("[id='{{$name}}']").on('change', function(e, batchLength){
        onChangeDropdown4({
            name:"{{$name}}", 
            lineType:"{{$lineType}}",
            table01Name:"{{$table01Name}}", 
            rowIndex:{{$rowIndex}}, 
            saveOnChange: {{$saveOnChange?1:0}},
            batchLength,
        })
    })
</script>