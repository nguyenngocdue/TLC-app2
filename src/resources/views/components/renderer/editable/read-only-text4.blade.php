<input id="{{$name}}" name="{{$name}}" component="editable/read-only-text4" value="{{$value}}" type="hidden" class="{{$classList}} {{$align}}">
@if($isId)
<a target='_blank' class='text-blue-500' href="{{$href}}">{{Str::makeId($title)}}</a>
@else
{{$title}}
@endif
<script>
    $("[id='{{$name}}']").on('change', function(e, batchLength){
        onChangeDropdown4({
            name:"{{$name}}", 
            table01Name:"{{$table01Name}}", 
            rowIndex:{{$rowIndex}}, 
            saveOnChange: {{$saveOnChange?1:0}},
            batchLength,
        })
    })
</script>