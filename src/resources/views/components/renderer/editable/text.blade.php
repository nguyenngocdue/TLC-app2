<input
    @readonly($readOnly)
    component="editable/text"
    id="{{$name}}"
    name="{{$name}}"
    value="{{$cell??$slot}}"
    type="text"
    placeholder="{{$placeholder}}" 
    class="{{$readOnly?"readonly":""}} block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm dark:focus:border-blue-600 focus:outline-none sm:text-sm"
    onChange='onChangeDropdown4({name:"{{$name}}", table01Name:"{{$table01Name}}", rowIndex:{{$rowIndex}}, saveOnChange: {{$saveOnChange?1:0}}})'
    >