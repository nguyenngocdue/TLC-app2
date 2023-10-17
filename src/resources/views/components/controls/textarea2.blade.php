<textarea 
    id="{{$name}}" 
    name="{{$name}}" 
    rows="{{$rows}}" 
    component="controls/textarea2"
    class="{{$classList}} {{$readOnly ? 'readonly' : ''}}" 
    placeholder="Type here..."
    {{$readOnly ? 'readonly': ''}}
    >{!!old($name, $value)!!}</textarea>
