<div class="relative w-full">
    @php
    $className = $icon ? 'pl-8' : '';
    @endphp
    @if($icon)
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none ">
        <i class="{{$icon}}"></i>
    </div>
    @endif
    <input
        component="editable/number"
        id="{{$name}}"
        name="{{$name}}"
        value="{{$cell??$slot}}"
        step='any' 
        type="number"
        placeholder="{{$placeholder}}" 
        class="{{$className}} text-right block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm"
        onChange='onChangeDropdown4({name:"{{$name}}", table01Name:"{{$table01Name}}", rowIndex:{{$rowIndex}}})'
        >
</div>