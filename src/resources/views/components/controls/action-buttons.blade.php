@if(!$isFixed)
<div class="flex justify-end rounded-lg dark:bg-gray-800 px-5">
@endif
    <div id="divActionButtons" class="{{$isFixed ? 'my-0' : 'my-5'}}">
        @if($buttonSave)
            @php $btnText = $action=='edit' ? 'Save' : 'Create New' @endphp
            @if($isFixed)<div class="text-right">@endif
                <x-renderer.button htmlType="submit" icon="fa-solid fa-floppy-disk"
                onClick="this.form.submit(); $('button').prop('disabled',true);" 
                >{{$btnText}}</x-renderer.button>
            @if($isFixed)</div>@endif
        @endif
        @if($action !== 'create')
            @foreach($actionButtons as $key => $button)
                @php
                    $hasIntermediateScreen = isset($propsIntermediate[$key])  && ! empty($propsIntermediate[$key]);
                    // $isClosedAt = (isset($button['closed_at'])&& $button['closed_at'] == true);
                    $isClosingOwnDoc = isset($button['closed_at']) && $button['is_close'] == true;
                    $buttonNext = "Next <i class='fa-regular fa-arrow-right'></i> (to <b>".$button['label']."</b>)";
                    $buttonTo = "<b>".$button['label']."</b>";
                    $buttonInnerHtml = $hasIntermediateScreen ? $buttonNext : $buttonTo;
                    $bgColor = "bg-".$button['color']."-".$button['color_index'];
                    $textColor = "text-".$button['color']."-".(1000-$button['color_index']);
                    $borderColor = "border border-".$button['color']."-".(1000-$button['color_index']);
                    $bgActiveColor = "bg-".$button['color']."-".($button['color_index'] + 100);

                    $classList = "border-opacity-20 border-2 px-2.5 py-2 inline-block font-medium text-sm leading-tight rounded focus:outline-transparent ";
                @endphp
                @if($isClosingOwnDoc)
                        @if($isFixed)<div class="text-right">@endif
                        <button type="button" 
                            title="You can't close your own document." 
                            class="{{$classList}} {{$bgColor}} {{$textColor}} {{$borderColor}} disabled:opacity-40"
                            disabled
                            >
                            {!! $buttonInnerHtml !!}
                        </button>
                        @if($isFixed)</div>@endif
                        @else
                        @if($isFixed)<div class="text-right">@endif
                        <button {{$hasIntermediateScreen ? "@click=toggleIntermediate('$key')" : "@click=changeStatus('$key');" }}
                                type='{{$hasIntermediateScreen ? "button" : "submit"}}'
                                title="{{ $hasIntermediateScreen ? 'Open an intermediate screen' : $actionButtons[$key]['tooltip']}}"
                                class="{{$classList}} {{$bgColor}} {{$textColor}} {{$borderColor}} hover:shadow-xl focus:shadow-xl active:shadow-xl"
                                {{-- onClick="$('button').prop('disabled',true);"  --}}
                                >
                                {!! $buttonInnerHtml !!}
                        </button>
                    @if($isFixed)</div>@endif
                @endif
            @endforeach
        @endif
    </div>
@if(!$isFixed)
</div>
@endif