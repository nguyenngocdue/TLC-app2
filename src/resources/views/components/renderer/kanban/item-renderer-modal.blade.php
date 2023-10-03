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
    <table class="w-full">
        @foreach($props as $prop)
            @if($prop['hidden_edit']) 
                @continue
            @endif
            
            <tr class="">
                @php 
                    // Log::info($prop); 
                    $name = $prop['column_name'];
                    $value = $item->{$name}; 
                    $table = $item->getTableName(); 
                @endphp
                <td class="p-1 border border-1 text-right">
                    <x-renderer.item-render-label
                        hiddenLabel="{{$prop['hidden_label']}}"
                        label="{{$prop['label']}}"
                        control="{{$prop['control']}}"
                        title="{{$prop['title'] ?? ''}}"

                        isRequired="{{$prop['isRequired'] ?? false}}"
                        iconJson="{{$prop['iconJson'] ?? false}}"
                        labelExtra="{{$prop['labelExtra'] ?? false}}"
                        />
                </td>
                <td class="p-1 border border-1">
                    <x-renderer.item-render-control
                        action="edit"
                        type="{{$table}}"
                        value="{{$value}}"

                        control="{{$prop['control']}}"
                        columnType="{{$prop['column_type']}}"
                        columnName="{{$prop['column_name']}}"
                        readOnly="{{$prop['read_only']}}"
                        label="{{$prop['label']}}"

                        placeholder="{{$prop['default-values']['placeholder']}}"
                        controlExtra="{{$prop['default-values']['control_extra']}}"
                        />
                </td>
            </tr>
        @endforeach
    </table>
</form>