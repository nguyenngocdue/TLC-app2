@php
//dump($content);
//dump($letterHeadId)
//dd($content);
dump($layoutClass)
@endphp

<div class="flex justify-center">
    <div class="py-4"> 
        <div class="{{$layoutClass}} items-center bg-white p-0 flex flex-col"
             {{-- style="background-image: url({{$background}});" --}}
             @if($background) style="background-image: url({{$background}});" @endif
            > 
            <!-- Head section with a border -->
            <div class="w-full border-2 border-gray-300 p-2 mb-1"> 
                @switch($letterHeadId)
                    @case (1)
                        <x-reports2.letter-head-report-type1/>
                    @break
                    @case (2)
                        <x-reports2.letter-head-report-type2/>
                    @break
                    @default
                        <x-reports2.letter-head-report-type1/>
                    @break
                @endswitch

            </div>

            <!-- Main content area -->
            <div class="w-full flex-grow flex flex-col items-center justify-center space-y-1 border-2 border-gray-300"> 
                <p class="text-base font-bold text-center">
                    This is the main content area. It fills most of the page and is designed to hold the bulk of your text or graphical information.
                </p>
                <div class=" flex flex-col items-left">
                    @foreach ($content as $key => $value)
                        @if (!is_array($value))
                            <p class="text-3xl text-green-700">{{$key}}: {{$value}}</p>
                        @endif
                    @endforeach                
                </div>
                {{-- Blocks --}}
                <div class="container mx-auto p-5">
                    <div class="grid grid-cols-12 gap-4">
                         <x-reports2.block-report :blocks="$blocks"/>
                    </div>
                </div>
                
            </div>

            <!-- Footer section with a border -->
            <div class="w-full border-2 border-gray-300 p-2 mt-1"> 
                @switch($letterFooterId)
                    @case (1)
                        <x-reports2.letter-footer-report-type1/>
                    @break
                    @case (2)
                        <x-reports2.letter-footer-report-type2/>
                    @break
                    @default
                        <x-reports2.letter-footer-report-type1/>
                    @break
                @endswitch
            </div>
        </div>
    </div>
</div>
