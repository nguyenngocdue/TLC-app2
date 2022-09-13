<div>
    @foreach($dataSource as $key => $value)
    @if($value->id*1 === $id*1)
    <input type="datetime-local" id="meeting-time" name="meeting-time" value="{{$label === "Created At" ? $value->created_at: $value->updated_at}}">
    @endif
    @endforeach
</div>
