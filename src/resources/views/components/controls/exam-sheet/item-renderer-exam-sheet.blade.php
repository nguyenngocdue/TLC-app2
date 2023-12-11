
@php $groupName = ''; @endphp

<div class="grid grid-cols-12 gap-2 p-4 w-full">
    <div class="col-span-2 border rounded p-2">
        <div class="sticky top-20 px-2 py-2">
            <x-navigation.table-of-contents :dataSource="$tableOfContents"/>
        </div>
    </div>
    <div class="col-span-10 border rounded p-2">
        <div class="" >
            @if($isOnePage)
                @foreach($tableOfContents as $index => $group)
                <div id="div-holder-for-sticky-to-push">
                    <x-renderer.heading 
                            class="sticky top-[68px] px-2 py-2 rounded bg-blue-500 z-[15]" 
                            id="exam_group_{{$group->id}}" 
                            level=4 
                            >{{$group->name}}</x-renderer.heading>
                    <div class="flex">
                        <div class="px-1"></div>
                        <div class="bg-blue-500 px-1 rounded"></div>
                        <div class="px-1"></div>                        
                        <div class="w-full">
                            <div id="group_{{$group->id}}">{{$group->description}}</div>
                            @foreach($dataSource as $item)
                                @php if($item->exam_tmpl_group_id != $group->id) continue; @endphp
                                <div class="my-2">
                                    <x-question-answer.question-answer :item="$item"/>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="p-2">
                    <x-renderer.button type='primary' htmlType="submit">Submit</x-render.button>
                </div>
            @endif
        </div>
    </div>
</div>