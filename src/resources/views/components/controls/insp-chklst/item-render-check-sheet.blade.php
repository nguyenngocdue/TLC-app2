@php
    $typeLine = str_replace('_shts','',$type);
    $idName = $typeLine.'_id';
    $value = $item->$idName;
@endphp

<div class="p-4 w-full md:w-3/4 xl:w-1/2 dark:bg-gray-800 rounded-lg">
    <x-renderer.item-render-props hiddenComponent="{{$showHeader?0:1}}" id={{$id}} :item="$item" width='' :dataSource="$dataSource" status={{$status}} action={{$action}} type={{$type}} modelPath={{$modelPath}} />
    {{-- <x-controls.insp-chklst.header-check-sheet :item="$item" :chklst="$chklst" :project="$project" :subProject="$subProject"/> --}}
    <x-renderer.heading level=4 xalign='center'>
        <span title="Checklist Sheet #{{$item->id}} ({{$item->description}})">{{strtoupper($item->name)}}</span>
    </x-renderer.heading>
    

    @if($type === 'qaqc_insp_chklst_shts')
    <div class="flex justify-between border bg-white rounded py-2">
        <div class="mx-4">
            <p>Project: {{$item->getChklst->getSubProject->name}}</p>
            <p>Module: {{$item->getChklst->getProdOrder->production_name}}</p>
        </div>
        <div class="mx-4">
            <img class="w-40" src="{{asset('logo/tlc.png')}}" />
        </div>
    </div>
    @endif
    @php
        $linesTmp = $lines;
        $checkPointIds = $lines->pluck('id');
        $lineIds = $lines->pluck('id');
        $groupedLines = $lines->groupBy($groupColumn,true);
        @endphp
        @foreach($groupedLines as $groupId => $lines)
            @php
                $groupName = $groupNames[$groupId] ?? "Untitled Group";
            @endphp
            <x-renderer.heading level=5 id="{{$groupColumn}}_{{$groupId}}_{{Str::slug($groupName)}}">
                <span title="{{$groupName}}">{{$groupName}}</span>
            </x-renderer.heading>
            <x-renderer.card tooltip="#{{$groupId}}" titleClass="text-lg">
                @foreach($lines as $rowIndex => $line)
                <x-controls.insp-chklst.check-point 
                    :sheet="$item" 
                    :line="$line" 
                    :checkPointIds="$checkPointIds" 
                    table01Name="table01" 
                    :rowIndex="$rowIndex" 
                    type="{{$typeLine}}"
                    readOnly="{{$isExternalInspector}}"
                />
                @endforeach
            </x-renderer.card>
            <div class="mb-8"></div>
        @endforeach
        
        @if($type==='qaqc_insp_chklst_shts' && $showSignOff)
            <x-renderer.card title="Nominated Third Party:">
                @php
                    $selectedMonitors1 = $item->getMonitors1()->pluck('id')->toArray();
                    $selectedMonitors1 = "[". join(",",$selectedMonitors1)."]";
                    @endphp
                <x-controls.has-data-source.dropdown2 readOnly={{$isExternalInspector?1:0}} type={{$type}} name='getMonitors1()' :selected="$selectedMonitors1" multiple={{true}}  />
            </x-renderer.card>
            
            @if($hasSignatureMulti)
            <x-controls.signature.signature-group2a
                title="Third Party Sign Off"
                category="signature_qaqc_chklst_3rd_party" 
                signableId='{{$id}}'
                :type="$type" 
                :item="$item"
            />
            @endif
        @endif

        {{-- has to be after the item-render-prop to override status and other values --}}
        <input type="hidden" name="tableNames[table01]" value="{{$typeLine}}_lines">
        {{-- Those are for main body, not the table --}}
        <input type="hidden" name="name" value="{{$item->name}}">
        <input type="hidden" name="{{$idName}}" value="{{$value}}">
        {{-- status id is for change status submit button --}}
        <input type="hidden" name="status" id='status' value="{{$status}}">        
        
</div>
<x-renderer.image-gallery-check-sheet :checkPointIds="$checkPointIds" :dataSource="$linesTmp" action='edit' />