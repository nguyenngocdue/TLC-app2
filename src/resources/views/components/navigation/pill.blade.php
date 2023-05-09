<div class="flex flex-col flex-wrap m1b-4 space-y-2 md:flex-row md:items-end md:space-x-1">
    @foreach($links as $link)
        <x-renderer.button disabled="{{$link['disabled']}}" href="{{$link['href']}}" title="{{$link['tooltip']??''}}" type="primary"> {{$link['title']}}</x-renderer.button>
    @endforeach
</div>
<div class="flex flex-col flex-wrap mb-2 space-y-2 md:flex-row md:items-end md:space-x-1">
    @foreach($links1 as $link)
        <x-renderer.button disabled="{{$link['disabled']}}" href="{{$link['href']}}" title="{{$link['tooltip']??''}}" type="primary"> {{$link['title']}}</x-renderer.button>
    @endforeach
</div>