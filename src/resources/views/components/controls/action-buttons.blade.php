<div class="flex justify-end dark:bg-gray-800 px-5">
    <div class="my-5">
        @if($buttonSave)
            @switch($action)
                @case('edit')
                <button type="submit" class="px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none active:bg-blue-800 active:shadow-lg">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Save</button>
                @break
                @case('create')
                <button type="submit" onclick="this.form.submit(); this.disabled=true; this.classList.add('disabled:opacity-40')" class="px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none active:bg-blue-800 active:shadow-lg">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Create</button>
                @break
                @default
                @break
            @endswitch
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

                    $classList = "border-opacity-20 px-2.5 py-2 inline-block font-medium text-sm leading-tight rounded focus:outline-transparent ";
                @endphp
                @if($isClosingOwnDoc)
                    <button type="button" 
                            title="You can't close your own document." 
                            class="{{$classList}} {{$bgColor}} {{$textColor}} {{$borderColor}} disabled:opacity-40"
                            disabled
                            >
                            {!! $buttonInnerHtml !!}
                        </button>
                        @else
                    <button {{$hasIntermediateScreen ? 'type=button @click=toggleIntermediate("'.$key .'")' : 'type=submit @click=changeStatus("'.$key .'")' }}
                        class="{{$classList}} {{$bgColor}} {{$textColor}} {{$borderColor}}  hover:shadow-xl focus:shadow-xl active:shadow-xl"
                        title="{{ $hasIntermediateScreen ? 'Open an intermediate screen' : $actionButtons[$key]['tooltip']}}"
                            >
                            {!! $buttonInnerHtml !!}
                    </button>
                @endif
            @endforeach
        @endif
    </div>
</div>