@php
    $value = $content->toArray();
@endphp
@if (!(sizeof($value) == 0 && ($control == 'attachment')) && !$hiddenLabel)
    <label class='p-2 h-full w-full border border-gray-600 text-base font-medium flex {{$valueColSpan[0]}} items-center {{$newLine ? 'justify-start' : 'justify-end'}} col-start-1'>{{$label}}</label>
@endif
@if(sizeof($value) > 0)
    @switch($control)
        @case('attachment')
            <div class='flex p-2 border border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left items-center'>
                @php
                    $isRenderSimple = $content->every(function($item){
                        return in_array($item->extension,['pdf','csv','zip']);
                    });
                @endphp
                @if($isRenderSimple)
                <div>
                    <x-print.attachment-simple :dataSource="$value"/>
                </div>
                @else
                    <x-renderer.attachment2 name='attachment' :value="$value" destroyable={{false}} showToBeDeleted={{false}} showUploadFile={{false}} />
                @endif
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
            <div class='p-2  border border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.checkbox-or-radio5 :relationships="$relationships" :value="$value" />
            </div>
            @break
        @case('comment')
            <div class='p-2  border border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.comment5 :relationships="$relationships" :value="$value" />
            </div>
            @break
        @case('signature_multi')
            <div class='p-2  border border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                @foreach($value as $signature)
                    <x-print.signature5 :relationships="$relationships" :dataSource="$value" />
                @endforeach
            </div>
            @break
        @case('signature')
            <div class='p-2  border border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.signature5 :relationships="$relationships" :dataSource="$value" />
            </div>
            @break
        @case('relationship_renderer')
            <div class='p1-2  border border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-controls.relationship-renderer2 id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} noCss={{true}} :item="$item"  numberOfEmptyLines="{{$numberOfEmptyLines}}"/>
            </div>
            @break
        @case('question_answer_renderer')
            <div class='p-2  border border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                <x-print.render-question-answer5 :item="$item" />
            </div>
            @break
        @default
            <span class='p-2  border border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>{{$content}}</span>
    @endswitch
@else
    @if($printMode == 'template')
        @switch($control)
            @case('relationship_renderer')
                        <div class='p1-2  border border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left'>
                            <x-controls.relationship-renderer2 id={{$id}} type={{$type}} colName={{$columnName}} modelPath={{$modelPath}} noCss={{true}} :item="$item" numberOfEmptyLines="{{$numberOfEmptyLines}}"/>
                        </div>
                    @break
            @default
        @endswitch
    @else
        @if($control !== 'attachment')
            <div class='p-2 border border-gray-600 text-sm font-normal {{$valueColSpan[1]}} {{$valueColSpan[2]}} text-left flex items-center'>
                (None)
            </div>
        @endif
    @endif

@endif
