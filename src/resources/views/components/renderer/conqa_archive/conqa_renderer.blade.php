{{-- @dump($attachments) --}}
{{-- @dump($users) --}}
{{-- @dump($checkpoints) --}}
{{-- @dump($signoffs) --}}
@foreach($sections as $section)
    <div class="bg-white my-10 mx-20 p-10 border">
        <x-renderer.heading level=3 xalign='center'>{{$section->name}}</x-renderer.heading>
        <br/>
        
        @foreach($section->checkpoints as $checkpointId)
            <div class="border my-2">
                @php
                    $checkpoint = $checkpoints->{$checkpointId}; 
                    $checkpointData = $data->checkpoints->{$checkpointId}; 
                    $checkpointAttachmentIds = $checkpointData->attachments;
                @endphp
               
                <div class="w-full bg-gray-200 p-1 font-semibold text-lg">{{$checkpoint->name}}</div>                
                <div class="p-4">
                    @switch($checkpoint->type)
                        @case("pass-fail")
                            @php 
                            $value = $checkpointData->value;
                            $bgPass = $value=='pass' ? 'bg-green-400' :"";
                            $bgFail = $value=='fail' ? 'bg-pink-400' :"";
                            $bgNA = $value=='not-applicable' ? 'bg-gray-400' :"";
                            @endphp

                            <div class="w-full my-2">
                                <div class="flex justify-center">
                                    <div class="border p-2 {{$bgPass}}">Pass</div>
                                    <div class="border p-2 {{$bgFail}}">Fail</div>
                                    <div class="border p-2 {{$bgNA}}">N/A</div>
                                </div>
                            </div>
                            @break
                        @default
                            Unknown type {{$checkpoint->type}}
                            @break
                    @endswitch
                    <br/>
                    
                    {{-- Attachments: {{count($checkpointAttachmentIds)}}
                    @foreach($checkpointAttachmentIds as $id)
                        <li>{{$id}}</li>
                    @endforeach --}}

                    
                    Documents:
                    <div class="">
                        @foreach($checkpointAttachmentIds as $checkpointAttachmentId)
                            @php $attachment = $attachments->{$checkpointAttachmentId} @endphp 
                            @if($attachment->type=='file' && !isset($attachment->deleted))
                                @if(isset($attachment->id))
                                    @if(isset($attachment->contentType))
                                        @if(in_array($attachment->contentType, $documentContentTypes))
                                            @php
                                            $ext = Str::getExtFromMime($attachment->contentType);
                                            if(str_starts_with($attachment->id, "file:")){
                                                $fileName = substr($attachment->id, strlen("file:")); 
                                                $src = $minioPath."/file/".$fileName.".".$ext;
                                            }
                                            // echo "DOT";
                                            @endphp
                                            <p class="ml-10">
                                                <i class="text-blue-400 fa-light fa-file"></i>
                                                <a class="text-blue-400" href="{{$src}}" target="_blank">{{$attachment->fileName}}</a>
                                            </p>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endforeach
                    </div>

                    Images:
                    <div class="grid grid-cols-5 gap-2 ">
                        @foreach($checkpointAttachmentIds as $checkpointAttachmentId)
                            @php $attachment = $attachments->{$checkpointAttachmentId} @endphp                       
                            @if($attachment->type=='file' && !isset($attachment->deleted))
                                @if(isset($attachment->id))
                                    @if(isset($attachment->contentType))
                                        @if(in_array($attachment->contentType, $mediaContentTypes))
                                            @php 
                                            $ext = Str::getExtFromMime($attachment->contentType);
                                            if(str_starts_with($attachment->id, "file:")){
                                                $fileName = substr($attachment->id, strlen("file:")); 
                                                $src = $minioPath."/file/".$fileName.".".$ext;
                                            }     
                                            //This is for Internal Sign-Off
                                            if(str_starts_with($attachment->id, "sig:")){
                                                $fileName = substr($attachment->id, strlen("sig:")); 
                                                $src = $minioPath."/sig/".$fileName.".".$ext;
                                            }  
                                            $uploadedBy = $users->{$attachment->createdBy}->name;
                                            $uploadedAt = \Carbon\Carbon::parse( $attachment->createdAt)->format("d/m/Y");
                                            @endphp
                                            <x-renderer.conqa_archive.conqa_attachment 
                                                name="{{$attachment->fileName}}"
                                                contentType="{{$attachment->contentType}}"
                                                src="{{$src}}"
                                                uploadedBy={{$uploadedBy}}
                                                uploadedAt={{$uploadedAt}}                                            
                                            /> 
                                        @endif                  
                                    @else
                                        Unable to load attachment as no content type {{$attachment->id}}
                                    @endif
                                @else
                                    Unable to load attachment without id
                                @endif
                            @endif
                        @endforeach
                    </div>
                    Signatures:
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($checkpointAttachmentIds as $checkpointAttachmentId)
                            @php $attachment = $attachments->{$checkpointAttachmentId} @endphp    
                            @if($attachment->type=='signature' && !isset($attachment->deleted))
                                @if(isset($attachment->id))
                                    @if(isset($attachment->contentType))
                                        @php 
                                        // dump($attachment->id);
                                        $ext = Str::getExtFromMime($attachment->contentType);
                                        if(str_starts_with($attachment->id, "sig:")){
                                            $fileName = substr($attachment->id, strlen("sig:")); 
                                            $src = $minioPath."/sig/".$fileName.".".$ext;
                                        }
                                        $uploadedBy = $users->{$attachment->createdBy}->name;
                                        $uploadedAt = \Carbon\Carbon::parse( $attachment->createdAt)->format("d/m/Y");
                                        @endphp

                                        <x-renderer.conqa_archive.conqa_attachment 
                                            name="{{$attachment->fileName}}"
                                            contentType="{{$attachment->contentType}}"
                                            src="{{$src}}"
                                            uploadedBy={{$uploadedBy}}
                                            uploadedAt={{$uploadedAt}}
                                        /> 
                                    @endif
                                @endif
                            @endif
                        @endforeach
                    </div>

                    Comments:
                    <div class="">
                        @foreach($checkpointAttachmentIds as $checkpointAttachmentId)
                            @php $attachment = $attachments->{$checkpointAttachmentId} @endphp
                            @if($attachment->type=='comment' && !isset($attachment->deleted))
                                @php
                                    $uploadedBy = $users->{$attachment->createdBy}->name;
                                    $uploadedAt = \Carbon\Carbon::parse( $attachment->createdAt)->format("d/m/Y");
                                @endphp
                                <p class="ml-4">
                                    <b>{{$uploadedBy}} ({{$uploadedAt}})</b>:
                                    {{$attachment->text}}
                                </p>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <x-renderer.conqa_archive.conqa_signoffs
                :checkpoint="$checkpoint"
                :attachments="$attachments"
                :users="$users"
                :minioPath="$minioPath"
            />

        @endforeach
    </div>
@endforeach