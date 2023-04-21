<div class="grid w-[40rem] grid-cols-4 space-x-2 rounded-xl bg-gray-200 p-2">
    @php 
    $class = [
        1 => 'peer-checked:bg-green-300 peer-checked:text-green-700',
        2 => 'peer-checked:bg-pink-300 peer-checked:text-pink-700',
        3 => 'peer-checked:bg-gray-300 peer-checked:text-gray-700',
        4 => 'peer-checked:bg-orange-300 peer-checked:text-orange-700',
        5 => 'peer-checked:bg-green-300 peer-checked:text-green-700',
        6 => 'peer-checked:bg-pink-300 peer-checked:text-pink-700',
        7 => 'peer-checked:bg-gray-300 peer-checked:text-gray-700',
        8 => 'peer-checked:bg-orange-300 peer-checked:text-orange-700',
    ];

    @endphp
    @foreach($options as $id => $option)
        <div>
            <input type="radio" 
                    name="{{$table01Name}}[qaqc_insp_control_value_id][{{$rowIndex}}]" 
                    id="radio_{{$line->id}}_{{$id}}" 
                    class="peer hidden" 
                    @checked($line->{"qaqc_insp_control_value_id"}==$id)  
                    value="{{$id}}"
                    />
            <label for="radio_{{$line->id}}_{{$id}}" 
                class="{{$class[$id] ?? $class[1]}} block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:font-bold 1peer-checked:text-white"
                title="#{{$id}}"
                >{{$option}}</label>
        </div>
    @endforeach
</div>