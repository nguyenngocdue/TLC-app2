@php
    $noBorder = $noBorder ?? false;
    $border = $noBorder ?"": "border" ;
    // dump($border);
@endphp
<div class="{{$border}} col-span-1 mx-auto text-center p-1 rounded">
    @switch($contentType)
        @case("image/png")
        @case("image/jpeg")
        @case("image/webp")
        @case("image/avif")
            <x-renderer.image spanClass="w-36 h-36" src="{{$src}}"/>
            @break

        @case("image/svg+xml")
            <x-renderer.image spanClass="w-72 h-36" src="{{$src}}"/>
            @break

        @case("video/mp4")
        @case("video/quicktime")
            <video controls class="w-36 h-36" src="{{$src}}" ></video>
            @break
    
        @default
            Unknown how to render {{$contentType}}
            @break
    @endswitch
    <p>{{$uploadedBy ?? ""}}</p>
    <p>{{$uploadedAt ?? ""}}</p>
</div>