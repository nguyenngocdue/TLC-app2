@php
    $value = $content->toArray();
    //if($content instanceof \Illuminate\Support\Collection){
    //}else {
    //    $tmp = $content['signature_multi'] ?? $content;
    //   $value = $tmp->toArray();
    //$dataSource = $content;
    //}
@endphp
@if (!(sizeof($value) == 0 && ($control == 'attachment')) && !$hiddenLabel)
    <label class='p-2 border-b border-gray-600 border-r text-base font-medium flex {{$valueColSpan[0]}} items-center {{$newLine ? 'justify-start' : 'justify-end'}} col-start-1'>{{$label}}</label>
@endif
@if(sizeof($value) > 0)
    @switch($control)
        @case('attachment')
            <div class='flex p-0 border text-left border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left items-center'>
                {{-- @php
                    $isRenderSimple = $content->every(function($item){
                        return in_array($item->extension,App\Utils\Constant::ARRAY_ONLY_NONE_MEDIA);
                    });
                @endphp
                @if($isRenderSimple)
                <div>
                    <x-print.attachment-simple :dataSource="$value"/>
                </div>
                @else --}}
                    <x-renderer.attachment2 openType='_blank' name='attachment' :value="$value" destroyable={{false}} showToBeDeleted={{false}} showUploadFile={{false}} />
                {{-- @endif --}}
            </div>
            @break
        @case('checkbox')
            <div class='p1-2  bor1der border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                @php
                    $selected = $content->pluck('id')->toArray();
                    $selected = '['.join(',',$selected).']';
                @endphp
                <x-controls.has-data-source.radio-or-checkbox2 type={{$type}} name={{$columnName}} selected={{$selected}} readOnly={{true}} multiple={{true}} />
            </div>
            @break
        @case('radio')
        @case('dropdown')
        @case('dropdown_multi')
            <div class='p-2 bord1er borde1r-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.checkbox-or-radio5 :relationships="$relationships" :value="$value" />
            </div>
            @break
        @case('comment')
            <div class='p-2  bor1der bo1rder-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.comment5 :relationships="$relationships" :value="$value" />
            </div>
            @break
        @case('signature_multi')
            <div class='p-2  bo1rder bo1rder-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.signature-multiple5 :relationships="$relationships" :dataSource="$content" : />
            </div>
            @break
        @case('signature')
            <div class='p-2 bor1der bor1der-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.signature5 :relationships="$relationships" :dataSource="$value" />
            </div>
            @break
        @case('relationship_renderer')
            <div class='p1-2  bord1er bor1der-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-controls.relationship-renderer2 id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} noCss={{true}} :item="$item"  numberOfEmptyLines="{{$numberOfEmptyLines}}"/>
            </div>
            @break
        @case('question_answer_renderer')
            <div class='p-2  bor1der bor1der-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.render-question-answer5 :item="$item" />
            </div>
            @break
        @default
            <span class='p-2  bor1der bor1der-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>{{$content}}</span>
    @endswitch
@else
    @if($printMode == 'template')
        @switch($control)
            @case('relationship_renderer')
                <div class='p1-2  bor1der bord1r-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                    <x-controls.relationship-renderer2 id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} noCss={{true}} :item="$item" numberOfEmptyLines="{{$numberOfEmptyLines}}"/>
                </div>
            @break
            @default
            @break
        @endswitch
    @else
        <div class='p-2 bord1er bord1er-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left flex items-center'>
            @if(!in_array($control, ['attachment', 'signature_multi']))
                {{-- @dump($control) --}}
                (None)
            @endif
        </div>
    @endif

@endif
