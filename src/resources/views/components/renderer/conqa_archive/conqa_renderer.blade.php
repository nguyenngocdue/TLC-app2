@foreach($sections as $section)
    <x-renderer.heading level=3 xalign='center'>{{$section->name}}</x-renderer.heading>
    <br/>
    @foreach($section->checkpoints as $checkpointId)
        @php
            $checkpoint = $checkpoints->{$checkpointId}; 
            $checkpointData = $data->checkpoints->{$checkpointId}; 
            $checkpointAttachmentIds = $checkpointData->attachments;

        @endphp
        {{$checkpoint->name}}
        <br/>
        @switch($checkpoint->type)
            @case("pass-fail")
                @php 
                $value = $checkpointData->value;
                $bgPass = $value=='pass' ? 'bg-green-400' :"";
                $bgFail = $value=='fail' ? 'bg-pink-400' :"";
                $bgNA = $value=='not-applicable' ? 'bg-gray-400' :"";
                @endphp

                <div class="flex">
                    <div class="border p-1 {{$bgPass}}">Pass</div>
                    <div class="border p-1 {{$bgFail}}">Fail</div>
                    <div class="border p-1 {{$bgNA}}">N/A</div>
                </div>
                {{-- Pass-Fail - {{$checkpointData->value}} --}}
                @break
            @default
                Unknown type {{$checkpoint->type}}
                @break
        @endswitch
        <br/>
        Attachments: {{count($checkpointAttachmentIds)}}
        @foreach($checkpointAttachmentIds as $checkpointAttachmentId)
            @php $attachment = $attachments->{$checkpointAttachmentId} @endphp
            @if(isset($attachment->id) && isset($attachment->contentType))
                @php 
                $fileName = substr($attachment->id, strlen("file:")); 
                $ext = Str::getExtFromMime($attachment->contentType);
                @endphp
                {{$fileName}}
                <x-renderer.image spanClass="w-36 h-36" src="{{$minioPath}}/{{$fileName}}.{{$ext}}"/>
            @endif
        @endforeach
        <br/>
    @endforeach
    <hr/>
@endforeach