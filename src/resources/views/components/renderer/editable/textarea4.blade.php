{{-- @if($readOnly)
<div class="p-2">{{$cell??$slot}}</div>
@endif --}}
<textarea 
    @readonly($readOnly)
    component="editable/textarea4"
    id="{{$name}}"
    name="{{$name}}"
    rows="3" 
    class="{{$classList}} {{$readOnly?"readonly":""}} {{$readOnly?"hidden1":""}}" 
    placeholder="Type here..."
    >{{old($name, $cell??$slot)}}</textarea>