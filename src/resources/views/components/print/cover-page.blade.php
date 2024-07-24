<div class="border p-div-10">
    QWERTYUI
</div>

<div class="border1 p1t-48 p1b-10 p-10vw">
    {{-- <div class="text-5xl">OPTION 4</div> --}}
    <div class="p-4">
        <img class="w-1/2 mx-auto" src="{{ asset('logo/tlc.png') }}">
        <div class="w-full text-center font-bold text-2xl-vw">{{config('company.name')}}</div>
    </div>

    <div class="p-8">
        <img class="w-3/4 mx-auto rounded shadow-1" src="{{ $src }}">
    </div>

    <div class="text-center font-bold text-3xl-vw pt-10 pb-4">
        {{$project->description }} ({{$project->name}})
    </div>
    
    <div class="text-center font-semibold mx-auto space-y-4 text-lg-vw ">        
        {{-- <div class="flex mx-auto w-3/4">
            <span class="w-1/3 text-right" title="Sub-Project Name (#{{$subProject->id}})">Sub-Project Code:</span><span class="w-2/3">{{$subProject->name}}</span>
        </div> --}}
        <div class="flex mx-auto w-3/4">
            <span class="w-1/3 text-right" title="Template Shortname (#{{$template->id}})">Checklist Name:</span><span class="w-2/3">{{$template->short_name}}</span>
        </div>
        <div class="flex mx-auto w-3/4">
            <span class="w-1/3 text-right" title="Routing Name (#{{$routing->id}})">Product Type:</span><span class="w-2/3">{{$routing->name}}</span>
        </div>
        <div class="flex mx-auto w-3/4">
            <span class="w-1/3 text-right" title="Production-Order Name (#{{$prodOrder->id}})">Product Name:</span><span class="w-2/3">{{$prodOrder->name}}</span>
        </div>
        {{-- <div class="flex mx-auto w-3/4">
            <span class="w-1/3 text-right">Product Code:</span><span class="w-2/3">{{$prodOrder->production_name}}</span>
        </div> --}}
    </div>

    <div class="flex justify-center items-center pt-64 text-md-vw">
        Powered by <img class="w-32 items-center" src="{{ asset('logo/moduqa.svg') }}" alt="logo">
    </div>
</div>
<x-renderer.page-break />