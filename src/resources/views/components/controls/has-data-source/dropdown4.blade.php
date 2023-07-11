@if($readOnly)
    @if($multiple)
        Not implemented yet
    @else 
        @php 
        $selectedArray = json_decode($selected); 
        $label = $nameless ? "Nameless #$selected" : DB::table($table)->whereIn('id',$selectedArray)->pluck('name');
        $selected = (empty($selectedArray)) ? null : $selectedArray[0]; 
        @endphp
        <input id='{{$id}}' name='{{$name}}' class='{{$classList}} readonly' value="{{$selected}}" type="hidden" readonly/>
        <div class="px-2" title="#{{$selected}}">{{$label[0]??""}}</div>
    @endif
@else
    <select id='{{$id}}' name='{{$name}}' {{$multiple ? "multiple" : ""}} class='{{$classList}}'
    ></select>
    <script>
        $(document).ready(()=>{
            const params = {id:'{{$id}}', table01Name: '{{$table01Name}}', selectedJson: '{!! $selected !!}', table: '{{$table}}', batchLength: {{$batchLength}}}
            documentReadyDropdown4(params)
            // console.log("Document ready dropdown4")
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