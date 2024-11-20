<div class="md:px1-4 flex-grow flex-shrink-0 w-full overflow-x-auto">  
    <div class="w-90vw items-center bg-white box-border p-4vw mx-auto">
        <div class="border1 p-4vw">
            {{-- <div class="text-5xl">OPTION 4</div> --}}
            <div class="p-4vw">
                <img class="w-7/12 mx-auto" src="{{ asset('logo/tlc.png') }}">
                <div class="w-full text-center font-bold text-2xl-vw">{{config('company.name')}}</div>
            </div>

            {{-- <div class="p-4vw"> --}}
                <img class="mx-auto rounded shadow-1 " src="{{ $avatar }}">
            {{-- </div> --}}

            <div class="text-center font-bold text-3xl-vw p-4vw">
                {{$title}}
            </div>
            
            <div class="text-center font-semibold mx-auto text-lg-vw "> 
                @foreach($dataSource as $key => $value)
                <div class="flex mx-auto w-3/4 gap-4 border1">
                    <span class="w-1/2 text-right" title="">{{$key}}:</span>
                    <span class="w-1/2 text-left">{{$value}}</span>
                </div>
                @endforeach
            </div>

            <div class="p-8vw block" role="divider"></div>

            <div class="flex justify-center items-center text-md-vw">
                Powered by <img class="w-1/6 items-center" src="{{ asset('logo/moduqa.svg') }}" alt="logo">
            </div>
        </div>
    </div>
</div>