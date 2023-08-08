@if($deaf && $readOnlyStr)
    @if($multipleStr)
        Not implemented yet for multiple dropdown4
    @else 
        @php 
        $selectedArray = json_decode($selected); 
        // $label = $nameless ? ["Nameless #$selected"] : DB::table($table)->whereIn('id',$selectedArray)->pluck('name');
        $selected = (empty($selectedArray)) ? null : $selectedArray[0]; 
        @endphp
        <input id='{{$id}}' name='{{$name}}' class='{{$classList}} readonly' value="{{$selected}}" type="hidden" readonly/>
        <div class="px-2" title="#{{$selected}}">{{$label[0]??""}}</div>
        <div id='div_{{$id}}' class="px-2" title="#{{$selected}}"></div>
        <script>
            label = {{$nameless?"true":"false"}} ? "Nameless #{{$selected}}" : ki['{{$table}}'][{{$selected}}]['name'];
            $("[id='div_{{$id}}']").html(label)
        </script>
    @endif
@else
    <select id='{{$id}}' name='{{$name}}' {{$multipleStr}} {{$readOnlyStr}} class='{{$classList}}'
    ></select>
    <script>
        $(document).ready(()=>{
            const dropdownParams = { batchLength: {{$batchLength}}, onLoad:true};
            const params1 = {id:'{{$id}}', table01Name: '{{$table01Name}}', selectedJson: '{!! $selected !!}', table: '{{$table}}', dropdownParams};
            documentReadyDropdown4(params1)
            /* console.log("Document ready dropdown4") */
        })
    </script>
@endif

<script>
    $("[id='{{$name}}']").on('change', function(e, dropdownParams){
        onChangeDropdown4({
            name:"{{$name}}", 
            lineType:"{{$lineType}}",
            table01Name:"{{$table01Name}}", 
            rowIndex:{{$rowIndex}}, 
            saveOnChange: {{$saveOnChange?1:0}},
            dropdownParams,
        })
    })
</script>