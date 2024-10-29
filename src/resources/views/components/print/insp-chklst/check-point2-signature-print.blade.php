<div class="flex items-center">
    <div class="w-6/12 flex justify-end">
        <x-controls.signature.signature2a 
            name='signature{{$checkpoint->id}}' 
            value='{{$checkpoint->value}}' 
            readOnly=1 
            />
    </div>

    <div class="w-6/12 flex items-center">
        <x-print.insp-chklst.check-point2-inspector-print :checkpoint="$checkpoint" />
    </div>
</div>