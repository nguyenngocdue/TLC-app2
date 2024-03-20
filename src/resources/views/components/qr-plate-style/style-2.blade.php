<div class="my-5">
    <div class="mt-1 border border-gray-800 rounded-3xl mx-5 text-center" style1="width: 600px;">&lt;--- scale to 8 cm ---&gt;</div>
    <div component="fixedWidth" class="border border-gray-800 rounded-3xl bg-white p-1 mx-5 my-1" style1="width: 600px;">
        <div class="">
            <div class="flex flex-col items-center" component="QR Code Group">
                <div class="w-48 h-32 flex items-center justify-center">
                    <div>
                        <img width="200px" src="{{$comLogo}}" />
                        <div class="text-center uppercase">{{$comWebsite}}</div>
                    </div>
                </div>
                <span id="{{$route}}" class=""></span>
                <div class="grid grid-cols-12 w-full m-6">
                    <div class="col-span-5 text-right mr-2 font-semibold">PROJECT</div><div class="col-span-7 uppercase">{!! $projectName !!}</div>
                    <div class="col-span-5 text-right mr-2 font-semibold">SERIAL NUMBER</div><div class="col-span-7 uppercase">{{$item["name"]}}</div>
                    <div class="col-span-5 text-right mr-2 font-semibold">SIZE</div><div class="col-span-7 uppercase">{!! $length ?? $red !!}M × {!!$width?? $red!!}M × {!!$height?? $red!!}M</div>
                    <div class="col-span-5 text-right mr-2 font-semibold">WEIGHT</div><div class="col-span-7 uppercase">{!!$weight?? $red!!} TONS</div>
                    {{-- <div class="col-span-5 text-right mr-4 font-semibold">MFG YEAR</div><div class="col-span-7 uppercase">{!!$manufacturedYear?? $red!!}</div> --}}
                    {{-- <div class="col-span-5 text-right mr-4 font-semibold">PLOT NUMBER</div><div class="col-span-7 uppercase">{!!$plotNumber?? $red!!}</div> --}}
                </div>
            </div>
        </div>
        {{-- <div class="text-center mt-2">{{$comName}}</div> --}}
    </div>
    <script>
    new QRCode(document.getElementById("{{$route}}"),{
        text: "{{$route}}",
        width: 140,
        height: 140,
    },)
    </script>
</div>