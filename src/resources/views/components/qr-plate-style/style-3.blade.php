<div class="my-5">
    <div class="mt-1 border border-gray-800 rounded-3xl mb-1 text-center">&lt;--- scale to 7 cm ---&gt;</div>
    <div component-name="square border for screw" class="border border-black p-1" >
        <div component="fixedWidth" class="border border-gray-800 rounded-6xl bg-white p-1 text-blue-600"
            style="width: 7cm; min-width: 7cm; max-width: 8cm; height: 8cm; min-height: 8cm; max-height: 8cm;">
            <div class="">
                <div class="flex flex-col items-center" component="QR Code Group">
                    <div class="w-48 mb-2 flex items-center justify-center">
                        <div>
                            <img width="128px" src="{{$comLogo}}" />
                            <div class="text-center text-md lowercase">{{$comWebsite}}</div>
                        </div>
                    </div>

                    <span id="{{$route}}" class=""></span>
                    <div class="text-black text-xs font-semibold w-full mt-6 mb-2">
                        <div class="text-center uppercase">{!! $projectName !!}</div>
                        <div class="text-center ">Module ID: {{$item["name"]}}</div>
                        <div class="text-center uppercase">{!! $length ?? $red !!}M × {!!$width?? $red!!}M × {!!$height?? $red!!}M</div>
                        <div class="text-center uppercase">{!!$weight?? $red!!} TONS</div>
                        {{-- <div class="col-span-5 text-right mr-4 font-semibold">MFG YEAR</div><div class="col-span-7 uppercase">{!!$manufacturedYear?? $red!!}</div> --}}
                        {{-- <div class="col-span-5 text-right mr-4 font-semibold">PLOT NUMBER</div><div class="col-span-7 uppercase">{!!$plotNumber?? $red!!}</div> --}}
                    </div>
                </div>
            </div>
            {{-- <div class="text-center mt-2">{{$comName}}</div> --}}
        </div>
    </div>
    <script>
    new QRCode(document.getElementById("{{$route}}"),{
        text: "{{$route}}",
        width: 128,
        height: 128,
    },)
    </script>
</div>