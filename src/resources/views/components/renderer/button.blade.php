<button 
    type="{{$htmlType}}" 
    class="{{$className}}" 
    name="{{$name}}" 
    value="{{$value}}" 
    title="{{$title}}"
    onClick="{!! $onClick !!}"
    >{{$slot}}</button> 
{{-- DO NOT ENTER between <button> and innerHTML, otherwise button.firstChild will be wrong, icon of trash in Editable Table will not change --}}