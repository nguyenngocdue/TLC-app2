@php
    $user = null;
    if($getUser ?? 0) $user = $checkpoint->getUser;
    elseif($checkpoint->getInspector) $user = $checkpoint->getInspector;
@endphp

@if($user)
    @php
        $src = ($avatar = $user?->getAvatar?->url_thumbnail) ? app()->pathMinio($avatar): "/images/avatar.jpg";
        @endphp
    <img src="{{$src}}" class="ml-2 mr-1 rounded-full" style="width: 14%;">
    <div class="text-xs-vw font-bold">
        <div>
            {{$user?->name}}
        </div>
        <div>
            {{$checkpoint->updated_at->format('d/m/Y')}}
        </div>
    </div>
@endif