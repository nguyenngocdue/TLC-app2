@php
     $readOnly = $readOnly || ($line->value && ($cuid != $uid));
@endphp
<div class="flex justify-center">
     <div>
          <div style="width:212px;" class="mx-auto">
               <x-controls.signature.signature2a
                    name="{{$table01Name}}[value][{{$rowIndex}}]"  
                    value="{{$line->value}}"
                    signatureUserId="{{$uid}}"

                    readOnly="{{$readOnly}}"
                    lineId="{{$line->id}}"
               />
               @if(!$line->value)
               <input type="hidden" name="{{$table01Name}}[inspector_id][{{$rowIndex}}]" value="{{$cuid}}" />
               @endif

               @if($line->value && $uid)                
                    <x-renderer.avatar-user uid="{{$uid}}" flipped=1 content="{{$timestamp}}"></x-renderer.avatar-user> 
               @endif
          </div>
     </div>
</div>