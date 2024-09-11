{{-- $readOnlyStr: if obmit this, when validation fail, the dropdown become deaf --}}
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
        <div id='div_{{$id}}' class="px-2 whitespace-nowrap" title="#{{$selected}}"></div>
        <script>
            label = (l = ki['{{$table}}']?.[{{$selected}}]?.['name'] ) ? l : "Nameless #{{$selected}}"; ;
            $("[id='div_{{$id}}']").html(label)
        </script>
    @endif
@else
    @php
        $selectedId = null;
        $selectedArray = json_decode($selected);
        if(sizeof($selectedArray) == 1)$selectedId = $selectedArray[0];  
        $editHref = route("{$table}.edit", $selectedId);      
    @endphp
    <div class="flex">
    <select 
            id='{{$id}}' 
            {{-- letUserClear='{{$let_user_clear}}' --}}
            letUserOpen={{($let_user_open??false)?'true':'false'}}
            name='{{$name}}' 
            {{$multipleStr}} 
            {{$readOnlyStr}} 
            class='{{$classList}}'></select>
            @if($let_user_open)                
                <x-renderer.button 
                    id="{{$id}}_edit_button"
                    class="h-full"
                    icon="fa-duotone fa-share-from-square" 
                    href="{{$editHref}}"
                    target="_blank"
                    ></x-renderer.button>
            @endif
    </div>

    <script>
        $(document).ready(()=>{
            const id = '{{$id}}';
            const table01Name = '{{$table01Name}}';
            const selectedJson = '{!! $selected !!}';
            const table = '{{$table}}';
            const letUserOpen = {{$let_user_open?1:0}};
            const letUserClear = {{$let_user_clear?1:0}};
            const letUserChooseWhenOneItem = {{$let_user_choose_when_one_item?1:0}};
            const dropdownParams = { batchLength: {{$batchLength}}, onLoad:true};
            const params1 = {id, table01Name, selectedJson, table, letUserClear, letUserChooseWhenOneItem, letUserOpen, dropdownParams};
            documentReadyDropdown4(params1)
            // console.log("Document ready {{$name}}  dropdown4")
        })
    </script>
@endif

<script>
    $("[id='{{$id}}']").on('change', function(e, dropdownParams){
        onChangeDropdown4({
            name:"{{$name}}", 
            id:"{{$id}}",
            lineType:"{{$lineType}}",
            table01Name:"{{$table01Name}}", 
            rowIndex:{{$rowIndex}}, 
            saveOnChange: {{$saveOnChange?1:0}},
            dropdownParams,
        })
        const letUserOpen = {{$let_user_open?1:0}};
        if(letUserOpen){
            const control = getEById("{{$id}}_edit_button_ahref")
            const href = `/dashboard/rp_blocks/${e.target.value}/edit`
            control.attr('href', href);
        }
    })
</script>