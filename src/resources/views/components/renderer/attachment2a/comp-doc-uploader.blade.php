<div class="flex items-center gap-1 truncate" title="Uploaded by {{$attachment['uploader']['display_name']}} (#{{$attachment['uploader']['id']}})">
    @if(!$hideUploader)
        <img style="width:4%" class="rounded-full" src="{{$attachment['uploader']['avatar_src']}}"  />
        <span class="text-sm-vw">{{$attachment['uploader']['first_name']}}</span>
    @endif
    @if(!$hideUploadDate)
        <p class="text-sm-vw">{{date('d/m/Y',strtotime($attachment['created_at'] ?? ''))}}</p>
    @endif
</div>