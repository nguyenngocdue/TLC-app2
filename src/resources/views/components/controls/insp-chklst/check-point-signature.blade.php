<div class="flex justify-center">
     <div>
          <div class="w-[340px] mx-auto">
               <x-controls.signature.signature2a
                    id="{{$table01Name}}[value][{{$rowIndex}}]"  
                    name="{{$table01Name}}[value][{{$rowIndex}}]"  
                    ownerIdColumnName="{{$table01Name}}[inspector_id][{{$rowIndex}}]"
                    value="{{$line->value}}"
                    signedPersonId="{{$line->inspector_id}}"
               />
               {{-- When user hasnt signed yet --}}
               @if($user) 
                    <x-renderer.avatar-user size="xlarge" uid="{{$user['id']}}" flipped=1 content="Signed at {{$user['timestamp']}}"></x-renderer.avatar-user> 
               @endif
          </div>
     </div>
</div>