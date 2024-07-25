<div class="p-4 w-full {{$width}} {{$hiddenComponent}} grid grid-cols-12 px-4 bg-white dark:bg-gray-800 rounded-lg">
    @foreach($dataSource as $prop)
    {{-- @php dump($prop) @endphp --}}
    @php
    if($prop){
        $label = $prop['label'];
        $properties = $prop['properties'];
        $columnName = $prop['columnName'];
        $numericScale = $prop['numericScale'];
        $new_line = $prop['new_line'];
        $columnType = $prop['columnType'];
        $align = $prop['align'];
        $control = $prop['control'];
        $col_span = $prop['col_span'];
        $classColSpanLabel = $prop['classColSpanLabel'];
        $classColStart = $prop['classColStart'];
        $classColSpanControl = $prop['classColSpanControl'];
        $value = $prop['value'];
        $title = $prop['title'];
        $default_value = $prop['default_value'];
        $labelExtra = $prop['labelExtra'];
        $placeholder = $prop['placeholder'];
        $controlExtra = $prop['controlExtra'];
        $textareaRows = $prop['textareaRows'];
        $iconJson = $prop['iconJson'];
        $hiddenRow = $prop['hiddenRow'];
        $hiddenLabel = $prop['hiddenLabel'];
        $readOnly = $prop['readOnly'];
        $isRequired = $prop['isRequired'];
    }
    @endphp
    {{-- @php $prop ? extract($prop) : null; @endphp --}}
        @if($prop)
            @php $readOnly = $hasReadOnly || $readOnly; @endphp
            <div class='col-span-{{$col_span}} {{$hiddenRow}}'>
                <div class='grid grid-row-1'>
                    <div class='grid grid-cols-12 items-center content-start'>
                        @if($columnType === 'static')
                            <div class='col-span-12 text-left'>
                                <x-renderer.item-render-static 
                                        control="{{$control}}"
                                        label="{{$label}}"
                                        title="{{$title}}"
                                        xalign="{{$align}}"
                                        labelExtra="{{$labelExtra}}"
                                    />
                            </div>
                        @else
                            <div class='col-start-1 col-span-12 sm:{{$classColSpanLabel}}  {{$prop['new_line'] === 'true' ? "text-left" : "text-left sm:text-right" }} '>
                                <x-renderer.item-render-label
                                        :item="$item"
                                        :prop="$prop"
                                        
                                        hiddenLabel="{{$hiddenLabel}}"
                                        title="{{$title}}"
                                        control="{{$control}}"
                                        action="{{$action}}"
                                        label="{{$label}}"
                                        isRequired="{{$isRequired}}"
                                        iconJson="{!! $iconJson !!}"
                                        labelExtra="{{$labelExtra}}"
                                />
                            </div>
                            <div class="{{$classColStart}} sm:{{$classColSpanControl}} col-span-12 pb-2 text-left">
                                @php $value2 = $value2 ?? "" @endphp
                                <x-renderer.item-render-control
                                    :item="$item"
                                    :prop="$prop"
                                    :valueArray="$value"
                                    :value="$value"
                                    :value2="$value2"
                                    
                                    control="{{$control}}"
                                    columnName="{{$columnName}}"
                                    controlExtra="{{$controlExtra}}"
                                    action="{{$action}}"
                                    type="{{$type}}"
                                    readOnly="{{$readOnly}}" 
                                    label="{{$label}}"
                                    placeholder="{{$placeholder}}"
                                    numericScale="{{$numericScale}}"
                                    id="{{$id}}"
                                    modelPath="{{$modelPath}}"
                                    status="{{$status}}"
                                    defaultValue="{{$default_value}}"
                                    columnType="{{$columnType}}"
                                    textareaRows="{{$textareaRows}}"
                                    />
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>