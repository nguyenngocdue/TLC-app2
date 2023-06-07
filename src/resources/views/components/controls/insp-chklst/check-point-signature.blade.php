<div class="flex justify-center">
     <div>
          <div class="w-96 h-36">
               <x-controls.signature2 
                    id="{{$table01Name}}[value][{{$rowIndex}}]"  
                    name="{{$table01Name}}[value][{{$rowIndex}}]"  
                    ownerIdColumnName="{{$table01Name}}[inspector_id][{{$rowIndex}}]"
                    value="{{$line->value}}"
               />
          </div>
          {{-- When user hasnt signed yet --}}
          @if($user) 
               <x-controls.insp-chklst.name-position :user="$user" />
          @endif
     </div>
</div>