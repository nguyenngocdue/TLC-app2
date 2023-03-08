@if($readOnly)
    @php
        $selectedDecode =json_decode($selected);
        $label = DB::table($table)->whereIn('id',$selectedDecode)->pluck('name');
    @endphp
    @if($multipleStr)
        <span class="select2 select2-container select2-container--default">
            <span class="selection">
                <span class="select2-selection select2-selection--multiple {{$readOnly ? 'readonly' : ''}}">
                    <ul class="select2-selection__rendered">
                        @foreach($selectedDecode as $key => $value)
                        <input 
                        type="hidden"
                        id="{{$id}}" 
                        name="{{$name}}" 
                        value="{{$value}}"
                        class='{{$className}} {{$readOnly ? 'readonly' : ''}}'
                        {{$readOnly ? 'readonly' : ''}} 
                        />
                        <li class="select2-selection__choice">{{$label[$key]}}</li>
                    @endforeach
                    </ul>
                    {{-- <svg aria-hidden="true" class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg> --}}
                </span>
            </span>
        </span>
    @else
    <div class="flex-shrink-0 inline-flex justify-between {{$className}} {{$readOnly ? 'readonly' : ''}}">
        <input 
        type="hidden"
        id="{{$id}}" 
        name="{{$name}}" 
        value="{{...$selectedDecode}}"
        class='{{$className}} {{$readOnly ? 'readonly' : ''}}'
        {{$readOnly ? 'readonly' : ''}} 
        />
        {{$label[0]}}
        <svg aria-hidden="true" class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </div>
    @endif
    
@else

    <select id='{{$id}}' name='{{$name}}' onChange='onChangeDropdown2("{{$name}}")' {{$multipleStr}}
    controlType='dropdown'
    class='{{$className}}'
    >
    </select>
    
<script>
    // params = {
    //     id: "{{$id}}"
    //     , name: "{{$name}}"
    //     , className: "{{$className}}"
    //     , multipleStr: "{{$multipleStr}}"
    // }
    // console.log(params)
    // document.write(Dropdown2(params))
    params2 = {id: '{{$id}}',selectedJson: '{!! $selected !!}',table: "{{$table}}" }
    documentReadyDropdown2(params2)
    
</script>
@endif
{{-- , saveOnChange: "{{$saveOnChange?1:0}}" --}}