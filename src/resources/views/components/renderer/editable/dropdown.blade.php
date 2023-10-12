@if($readOnly)
@php
echo Blade::render("<x-renderer.status>".$selected."</x-renderer.status>");
@endphp
@endif
<select 
    id="{{$name}}" 
    name="{{$name}}" 
    component="editable/dropdown"
    style="{{$style}}"
    class="{{$classList}} {{$readOnly?"hidden":""}}">
    @foreach($cbbDataSource as $line)
    <option 
        title="{{$line['value']}}" 
        value="{{$line['value']}}" 
        @selected($selected==$line['value'] )
        >
        {{$line['title']}}
    </option>
    @endforeach
</select>