
@if($readOnly)
    <div class="{{$classList}} {{$readOnly ? 'readonly' : ''}}">
        <input 
        type="hidden"
        id="{{$name}}" 
        name="{{$name}}" 
        value="{{$value}}"
        class='{{$readOnly ? 'readonly' : ''}}'
        {{$readOnly ? 'readonly' : ''}} 
        />
        {{Str::headline( $value)}}
        <svg aria-hidden="true" class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </div>
@else
<div>
    {{-- @dump($options) --}}
    <select name="{{$name}}" id="select-dropdown-{{$name}}" class="w-full">
        <option value="0">(None)</option>
        @foreach($options as $key=>$title)
        <option value="{{$key}}" @selected($value===$key)>{{$title}}</option>
        @endforeach
    </select>
</div>
<script type="text/javascript">
    $('#select-dropdown-{{$name}}').select2({
        placeholder: "Please select"
        , allowClear: false
    });
</script>
@endif

