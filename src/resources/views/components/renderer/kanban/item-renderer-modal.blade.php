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
                @endphp
                <td class="p-1 border border-1 text-right">{{$prop['label']}}</td>
                <td class="p-1 border border-1">
                    <input class="px-2 py-1 w-full border border-1 rounded" name="{{$name}}" value="{{$value}}" >
                </td>
            </tr>
        @endforeach
    </table>
</form>