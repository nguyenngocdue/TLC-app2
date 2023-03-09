<input
    @readonly($readOnly)
    component="editable/number"
    id="{{$name}}"
    name="{{$name}}"
    value="{{$cell??$slot}}"
    step='any' 
    type="number"
    placeholder="{{$placeholder}}" 
    class="{{$bgColor}} text-right block w-full rounded-md border bg-white-bug-OTRL dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm dark:focus:border-blue-600 focus:outline-none sm:text-sm"
    onChange="{!! $onChange !!};changeBgColor(this,'{{$table01Name}}')"
    >