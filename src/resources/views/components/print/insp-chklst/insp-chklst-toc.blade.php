<div class="flex justify-center bg-body bg-only-print print-responsive">
    <div class="p-4vw w-90vw mx-auto bg-white">
        <x-print.letter-head5 showId="{{$entity->id}}" type="{{$type}}"  />
            <x-renderer.heading level=3 class='text-center uppercase font-bold p-1vw'>{{Str::singular($topTitle)}}</x-renderer.heading>
            <x-print.insp-chklst.check-point2-project-box-print :projectBox="$projectBox" />
            <x-renderer.heading level=4 class='text-center uppercase font-bold p-1vw'>{{$templateName}}</x-renderer.heading>
            {{-- @dump($sheets) --}}

            {{-- <div class="border border-gray-500"> --}}
                <div class="flex w-full">
                    <div class="border w-1/12 border-gray-500 px-2 py-1 text-center font-bold">
                        No.
                    </div>
                    <div class="border w-7/12 border-gray-500 px-2 py-1 text-center font-bold">
                        Sheet Name
                    </div>
                    <div class="border w-2/12 border-gray-500 px-2 py-1 text-center font-bold">
                        Progress (%)
                    </div>
                    <div class="border w-2/12 border-gray-500 px-2 py-1 text-center font-bold">
                        Status
                    </div>
                </div>

                @php $index = 1; @endphp
                @foreach($sheets as $sheet)
                    @if($sheet->status == 'not_applicable') @continue @endif
                    <div class="flex w-full">
                        <div class="border w-1/12 border-gray-500 px-2 py-1 text-center">
                            {{$index++}}
                        </div>
                        <div class="border w-7/12 border-gray-500 px-2 py-1 text-center text-blue-500 cursor-pointer">
                            <a href="#{{Str::slug($sheet->name)."_".$sheet->id}}">{{$sheet->name}}</a>                    
                        </div>
                        <div class="border w-2/12 border-gray-500 px-2 py-1 text-center">
                            {{$sheet->progress}}
                        </div>
                        <div class="border w-2/12 border-gray-500 px-2 py-1 text-center">
                            <x-renderer.status>
                                {{$sheet->status}}
                            </x-renderer.status>
                        </div>
                    </div>
                @endforeach
            {{-- </div> --}}
    </div>
</div>