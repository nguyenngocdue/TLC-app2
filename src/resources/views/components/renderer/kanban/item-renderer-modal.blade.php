<script>
    k = @json($listenerDataSource);
    ki = makeKi(k)

    listenersOfDropdown2 = @json($listeners2);
    filtersOfDropdown2 = @json($filters2);

    listenersOfDropdown4s = @json($listeners4);
    filtersOfDropdown4s = @json($filters4);
</script>

<form id="frmKanbanItem" action="">
{{-- @php Log::info($item); @endphp --}}
    <div class="grid grid-cols-12 gap-2">
        @foreach($props as $prop)
            @if($prop['hidden_edit']) 
                @continue
            @endif
            @php 
                // Log::info($prop); 
                $name = $prop['column_name'];
                $table = $item->getTableName(); 
                $value = $values->{$name}; 
            @endphp

            <div class="col-span-12 lg:col-span-3 xl:col-span-2">
                <x-renderer.item-render-label
                    hiddenLabel="{{$prop['hidden_label']}}"
                    label="{{$prop['label']}}"
                    control="{{$prop['control']}}"
                    title="{{$prop['column_name'] ?? ''}}"

                    isRequired="{{$prop['isRequired'] ?? false}}"
                    iconJson="{{$prop['iconJson'] ?? false}}"
                    labelExtra="{{$prop['labelExtra'] ?? false}}"
                    />
            </div>
            <div class="col-span-12 lg:col-span-9 xl:col-span-10">
                <x-renderer.item-render-control
                    action="edit"
                    type="{{$table}}"
                    value="{!! $value !!}"
                    
                    {{-- :prop="$prop"
                    :valueArray="$value" --}}

                    control="{{$prop['control']}}"
                    columnType="{{$prop['column_type']}}"
                    columnName="{{$prop['column_name']}}"
                    readOnly="{{$prop['read_only']}}"
                    label="{{$prop['label']}}"

                    placeholder="{{$prop['default-values']['placeholder'] ?? ''}}"
                    controlExtra="{{$prop['default-values']['control_extra'] ?? ''}}"
                    defaultValue="{{$prop['default-values']['default_value'] ?? ''}}"
                    numericScale="{{$prop['numeric_scale']}}"
                    />
            </div>
        @endforeach
    </div>
</form>