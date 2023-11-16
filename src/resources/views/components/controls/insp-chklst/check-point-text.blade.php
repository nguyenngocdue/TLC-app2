<div class="my-4 w-full">
        <input 
            class="border border-gray-300 rounded mx-0.5 p-1 w-full {{$readOnly?"readonly":""}}"
            name="{{$table01Name}}[value][{{$rowIndex}}]" 
            id="text_{{$line->id}}" 
            value="{{$line->value}}"
            @readonly($readOnly)
            >
</div>