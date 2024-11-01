<div class=" border border-gray-500">
    <div class="grid grid-cols-12 text-sm-vw">
    @foreach($projectBox as $key=>$item)
        <div class="col-span-2 border-t border-gray-300 bg-gray-200 text-right px-1 py-1">
            <span class="font-bold ">{{$key}}</span>
        </div>
        <div class="col-span-4 border-t border-gray-300 px-1 py-1">
            <span>{{$item}}</span>
        </div>
    @endforeach
    </div>
</div>