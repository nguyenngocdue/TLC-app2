<div class="flex justify-center">
     <div>
          <div class="w-[340px] mx-auto">
               <x-controls.signature.signature2a
                    name="{{$table01Name}}[value][{{$rowIndex}}]"  

                    value="{{$line->value}}"
                    signatureUserId="{{$line->inspector_id}}"
               />
               {{-- When user hasnt signed yet --}}
               @if($user) 
                    <x-renderer.avatar-user size="xlarge" uid="{{$user['id']}}" flipped=1 content="Signed at {{$user['timestamp']}}"></x-renderer.avatar-user> 
               @endif
          </div>
     </div>
</div>