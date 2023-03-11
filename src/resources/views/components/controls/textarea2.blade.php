<textarea 
    name="{{$name}}" 
    rows="10" 
    component="controls/textarea2"
    class="{{$classList}} {{$readOnly ? 'readonly' : ''}}" 
    placeholder="Type here..."
    {{$readOnly ? 'readonly': ''}}
    >{{old($name, $value)}}</textarea>
