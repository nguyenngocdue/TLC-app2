<div class="w-full">
        <textarea
            class="border-gray-300 bg-blue-100 border-2 rounded p-1 w-full {{$readOnly?"readonly":""}}"
            name="{{$table01Name}}[value][{{$rowIndex}}]" 
            id="text_{{$line->id}}" 
            placeholder="Enter value here"
            @readonly($readOnly)
            rows={{$rows}}
            onchange="updateInspectorId({{$line->id}}, {{$cuid}});"
        >{{$line->value}}</textarea>
</div>