@if($readOnly)
@php
echo Blade::render("<x-renderer.status>".$selected."</x-renderer.status>");
@endphp
@endif
<select 
    id="{{$name}}" 
    name="{{$name}}" 
    component="controls/dropdown5"
    class="{{$classList}} {{$readOnly?"hidden":""}}">
    @foreach($dataSource as $item)
    <option 
        class="flex justify-between"
        title="{{$item->description ?? $item['description']}}" 
        value="{{$item->id ?? $item['id']}}" 
        @selected($selected==($item->id ?? $item['id']))
        >
        @php
            $count = $item->getLines()->count() ?? 0;
        @endphp
        {{Str::makeId($item->id ?? $item['id'])}} - {{$item->name ?? $item['name']}} ({{$count}} lines) 
    </option>
    @endforeach
</select>