<div class="flex justify-center">
     <div>
          <div class="w-[340px] h-36">
               <x-controls.signature2 
                    id="{{$table01Name}}[value][{{$rowIndex}}]"  
                    name="{{$table01Name}}[value][{{$rowIndex}}]"  
                    ownerIdColumnName="{{$table01Name}}[inspector_id][{{$rowIndex}}]"
                    value="{{$line->value}}"
                    signedPersonId="{{$line->inspector_id}}"
               />
          </div>
          {{-- When user hasnt signed yet --}}
          @if($user) 
               <x-controls.insp-chklst.name-position :user="$user" />
          @endif
     </div>
</div>