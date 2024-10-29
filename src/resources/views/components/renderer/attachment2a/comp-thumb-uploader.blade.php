@if(!$hideUploader)
    <span class="flex items-center gap-1 mt-1 justify-center text-sm-vw" title="Uploaded by {{$attachment['uploader']['display_name']}} (#{{$attachment['uploader']['id']}})">
        <img style="width:17%" class="w1-6 rounded-full" src="{{$attachment['uploader']['avatar_src']}}" />
        {{$attachment['uploader']['first_name']}}
    </span>
@endif
@if(!$hideUploadDate)
    <span class="flex justify-center text-sm-vw">{{date('d/m/Y',strtotime($attachment['created_at'] ?? ''))}}</span>
@endif