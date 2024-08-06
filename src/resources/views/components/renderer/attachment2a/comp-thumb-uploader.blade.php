@if(!$hideUploader)
    <span class="flex items-center gap-1 mt-1 justify-center" title="Uploaded by {{$attachment['uploader']['display_name']}} (#{{$attachment['uploader']['id']}})">
        <img style="width:17%" class="w1-6 rounded-full" src="{{$attachment['uploader']['avatar_src']}}" />
        {{$attachment['uploader']['first_name']}}
    </span>
@endif
@if(!$hideUploadDate)
    <span class="flex justify-center">{{date('d/m/Y',strtotime($attachment['created_at'] ?? ''))}}</span>
@endif