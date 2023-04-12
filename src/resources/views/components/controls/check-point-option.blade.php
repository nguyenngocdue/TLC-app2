<div class="my-4">
    @foreach($options as $id => $name)
    <label for="radio_{{$line->id}}_{{$id}}" title="#{{$id}}"  class="border bg-gray-400 p-4 rounded mx-0.5 cursor-pointer">
        <input 
            @checked($line->value==$id) 
            
            name="{{$table01Name}}[qaqc_insp_control_value_id][{{$rowIndex}}]" 
            id="radio_{{$line->id}}_{{$id}}" 
            type="radio" 
            value="{{$id}}"
            > {{$name}}
    </label>
    @endforeach
</div>