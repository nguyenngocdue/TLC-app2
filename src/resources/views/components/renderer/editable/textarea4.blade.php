<textarea 
    @readonly($readOnly)
    component="editable/textarea4"
    id="{{$name}}"
    name="{{$name}}"
    rows="2" 
    class="{{$classList}} {{$readOnly?"readonly":""}}" 
    placeholder="Type here..."
    >{{old($name, $cell??$slot)}}</textarea>
