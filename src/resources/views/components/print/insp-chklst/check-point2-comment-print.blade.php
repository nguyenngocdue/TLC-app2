@if(!$checkpoint->insp_comments->isEmpty())
    {{-- @dump($checkpoint->insp_comments) --}}
    @foreach($checkpoint->insp_comments as $comment)
        @php
            $avatar = $comment->getOwner?->getAvatar?->url_thumbnail ?: "/images/avatar.jpg";
            $src = app()->pathMinio($avatar);
        @endphp
        <div class="flex gap-1">
            <div class="w-1/12" style="margin-top: 16px;">
                <img src="{{$src}}" class="w-3/4 rounded-full top-8" alt="">
            </div>
            <div class="w-11/12">          
                <div class="text-sm" style="top: 12px;">
                </div>
                <div class="w-full my-1">
                    <div class="relative bg-gray-200 px-4 py-2 border rounded-lg shadow-md">
                        <div class="font-bold text-xs-vw">
                            {{$comment->getOwner?->name}} 
                            ({{$comment->getOwner?->getPosition?->name}})
                            on {{$comment->created_at->format('d/m/Y')}} said:
                        </div>
                        <!-- Comment text -->
                        <p class="text-sm-vw">
                            {{$comment->content}}
                        </p>                
                        <!-- Balloon tail -->
                        <div
                            style="top: 26px; left:-6px;" 
                            class="absolute w-4 h-4 bg-gray-200 transform rotate-45 -translate-y-2"></div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif