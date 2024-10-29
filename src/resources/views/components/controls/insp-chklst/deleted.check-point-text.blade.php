<div class="w-full">
        <input 
            class="border-gray-300 bg-blue-100 border-2 rounded p-1 w-full {{$readOnly?"readonly":""}}"
            name="{{$table01Name}}[value][{{$rowIndex}}]" 
            id="text_{{$line->id}}" 
            value="{{$line->value}}"
            placeholder="Enter value here"
            @readonly($readOnly)
            >
</div>