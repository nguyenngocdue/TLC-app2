@if(isset($checkpoint->signoff))                        
    <div class="bg-green-100 border" style="min-height: 100px;">
        <div class="w-full bg-gray-200 p-1 font-semibold text-lg">{{$checkpoint->signoff->name}}</div>
        @if(isset($checkpoint->signoffs))
            {{-- Count: {{count($checkpoint->signoffs)}} --}}
            {{-- @dump($checkpoint->signoffs) --}}
            @foreach($checkpoint->signoffs as $signoff)
                @if(isset($signoff->attachments))
                    @foreach($signoff->attachments as $attachmentId)
                        @php 
                        $attachment = $attachments->{$attachmentId}; 
                        $uploadedBy = $users->{$attachment->createdBy}->name;
                        $uploadedAt = \Carbon\Carbon::parse( $attachment->createdAt)->format("d/m/Y");
                        @endphp
                        {{-- @dump($attachment) --}}
                        <div class="border m-4">
                            @if(isset($signoff->status)) 
                                    <div class="uppercase font-bold text-center text-white bg-green-400 p-2 rounded">
                                        <i class="fa fa-check"></i>
                                        {{$signoff->status }}
                                    </div>
                            @endif

                            @if(isset($attachment->contentType))
                                @php
                                $ext = Str::getExtFromMime($attachment->contentType);
                                if(str_starts_with($attachment->id, "wmsig:")){
                                    $fileName = substr($attachment->id, strlen("wmsig:")); 
                                    $src = $minioPath."/wmsig/".$fileName.".".$ext;
                                }
                                @endphp

                                <div class="p-2">
                                    <x-renderer.conqa_archive.conqa_attachment 
                                            name="{{$attachment->fileName}}"
                                            contentType="{{$attachment->contentType}}"
                                            src="{{$src}}"
                                            noBorder="true"
                                            {{-- uploadedBy={{$uploadedBy}} --}}
                                            {{-- uploadedAt={{$uploadedAt}} --}}
                                        /> 
                                </div>
                            @else
                                <div class="p-2">
                                    <b>{{$uploadedBy}} ({{$uploadedAt}}):</b> 
                                    {{$attachment->text}}
                                </div>
                            @endif
                        </div>
                    @endforeach
                    
                    @if(isset($signoff->approverDetails))
                    <div class="p-2">
                        <p><b>{{$signoff->approverDetails->name}}</b></p>
                        <p><b>{{$signoff->approverDetails->company ?? ""}}</b></p>
                        <p><i>{{$signoff->approverDetails->email ?? ""}}</i></p>
                    </div>
                    @endif
                @endif
            @endforeach
        @endif
    </div>
@endif