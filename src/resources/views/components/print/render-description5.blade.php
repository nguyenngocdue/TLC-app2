@php
    // $value = $content->toArray();
    if(is_array($content)){
        $value = $content;
    } else {
        $value = $content->toArray();
    }
    //if($content instanceof \Illuminate\Support\Collection){
    //}else {
    //    $tmp = $content['signature_multi'] ?? $content;
    //   $value = $tmp->toArray();
    //$dataSource = $content;
    //}
@endphp
@if(sizeof($value) > 0 && !$hiddenLabel)
    <label class='p-2 border-r border-gray-600 text-md-vw font-medium flex {{$valueColSpan[0]}} items-center {{$newLine ? 'justify-start' : 'justify-end'}} col-start-1'>{{$label}}</label>
@endif
@if(sizeof($value) > 0)
    @switch($control)
        @case('attachment')
            <div class='flex p-0 bord1er text-left bor1der-gray-600 text-sm-vw font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left items-center'>
                <x-renderer.attachment-group 
                {{-- <x-renderer.attachment2a  --}}
                    openType='_blank' 
                    name='attachment' 
                    :value="$value" 
                    destroyable={{false}} 
                    showToBeDeleted={{false}} 
                    showUploadFile={{false}} 
                    />
            </div>
            @break
        {{-- @case('checkbox') --}}
        @case('checkbox_2a')
            <div class='p1-2  bor1der border-gray-600 text-sm-vw font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                @php
                    $selected = $content->pluck('id')->toArray();
                    $selected = '['.join(',',$selected).']';
                @endphp
                <x-controls.has-data-source.radio-or-checkbox2 type={{$type}} name={{$columnName}} selected={{$selected}} readOnly={{true}} multiple={{true}} />
            </div>
            @break
        @case('radio')
        @case('dropdown')
        {{-- @case('dropdown_multi') --}}
        @case('dropdown_multi_2a')
            <div class='p-2 bord1er borde1r-gray-600 text-sm-vw font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.checkbox-or-radio5 :relationships="$relationships" :value="$value" />
            </div>
            @break
        @case('comment')
            <div class='p-2  bor1der bo1rder-gray-600 text-sm-vw font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.comment5 :relationships="$relationships" :value="$value" />
            </div>
            @break
        @case('signature_multi')
            <div class='p-2  bo1rder bo1rder-gray-600 text-sm-vw font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.signature-multiple5 :relationships="$relationships" :dataSource="$content" : />
            </div>
            @break
        @case('signature')
            <div class='p-2 bor1der bor1der-gray-600 text-sm-vw font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.signature5 :relationships="$relationships" :dataSource="$value" />
            </div>
            @break
        @case('relationship_renderer')                        
            <div class='p1-2  bord1er bor1der-gray-600 text-sm-vw font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-controls.relationship-renderer2 id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} noCss={{true}} :item="$item"  numberOfEmptyLines="{{$numberOfEmptyLines}}"/>
            </div>
            @break
        @case('question_answer_renderer')
            <div class='p-2  bor1der bor1der-gray-600 text-sm-vw font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.render-question-answer5 :item="$item" />
            </div>
            @break
        @default
            <span class='p-2  bor1der bor1der-gray-600 text-sm-vw font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>{{$content}}</span>
    @endswitch
@else
{{-- No need as the only print template is in SQT is obsolete --}}
    {{-- @if($printMode == 'template')
        @switch($control)
            @case('relationship_renderer')
                <div class='p1-2  bor1der bord1r-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                    <x-controls.relationship-renderer2 id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} noCss={{true}} :item="$item" numberOfEmptyLines="{{$numberOfEmptyLines}}"/>
                </div>
            @break
            @default
            @break
        @endswitch
    @endif --}}
@endif
