@php
    $selectedDecode =json_decode($selected);
@endphp
@if($readOnly)
    @php
    if(str_starts_with($table, "morph_to")){
        $labels = $selectedDecode;
    }else {
        $nameless = $nameless ?? false;
        $labels = DB::table($table)->whereIn('id',$selectedDecode)->pluck($nameless ? 'id':'name', 'id');
    }
    @endphp
    @if($multipleStr)
        <span class="select2 select2-container select2-container--default w-full">
            <span class="selection">
                <span class="select2-selection select2-selection--multiple {{$readOnly ? 'readonly' : ''}}">
                    <ul class="select2-selection__rendered">
                        @forelse($labels as $key => $value)
                        <input type="hidden" id="{{$id}}" name="{{$name}}" value="{{$key}}" class='{{$classList}} {{$readOnly ? 'readonly' : ''}}' {{$readOnly ? 'readonly' : ''}} />
                        <li title="#{{$key}}" class="select2-selection__choice">{{$value}}</li>
                        @empty
                        <div class="p-3"></div>
                        @endforelse
                    </ul>
                </span>
            </span>
        </span>
    @else
        {{-- @dump($labels) --}}
        @php 
            $title = is_array($labels) ? $labels[0] : $labels->first();
        @endphp
        <div title="#{{$selectedDecode[0] ?? ""}}" class="flex-shrink-0 inline-flex justify-between {{$classList}} {{$readOnly ? 'readonly' : ''}}">
            <input type="hidden" id="{{$id}}" name="{{$name}}" value="{{$selectedDecode[0] ?? ''}}" class='{{$classList}} {{$readOnly ? 'readonly' : ''}}' {{$readOnly ? 'readonly' : ''}} />
            <span>{{$title}}</span>
            <i class="fa-duotone fa-chevron-down"></i>
        </div>
    @endif
@else

    <select id='{{$id}}' name='{{$name}}' {{$multipleStr}} controlType='dropdown' onChange='onChangeDropdown2("{{$id}}")' 
        allowClear={{$allowClear?'true':'false'}} 
        letUserChooseWhenOneItem={{($letUserChooseWhenOneItem??false)?'true':'false'}} 
        class='{{$classList}}'
        ></select>

    <script>
        params2 = {
            id: '{{$id}}'
            , selectedJson: '{!! $selected !!}'
            , table: "{{$table}}"
            , allowClear: {{$allowClear ?'true':'false'}}
            , action: "{{$action ?? 'create'}}"
            , letUserChooseWhenOneItem: {{($letUserChooseWhenOneItem??false) ?'true':'false'}}
        }
        documentReadyDropdown2(params2)
        // console.log("Document ready dropdown2")

    </script>
@endif

