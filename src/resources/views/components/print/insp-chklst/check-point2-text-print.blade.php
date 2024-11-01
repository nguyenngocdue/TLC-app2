{{-- @dump($checkpoint) --}}
{{-- @dump($checkpoint->getControlGroup->getControlValues) --}}


<div class="flex items-center">
    <div class="w-6/12 flex">
        {{-- @dump($checkpoint->value) --}}
        {!! nl2br($checkpoint->value) !!}
    </div>

    <div class="w-6/12 flex items-center">
        <x-print.insp-chklst.check-point2-inspector-print :checkpoint="$checkpoint" />
    </div>
</div>
