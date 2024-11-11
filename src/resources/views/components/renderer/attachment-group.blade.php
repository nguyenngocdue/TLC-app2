<div class="flex w-full justify-center" component="attachment-group">
    @foreach($attachmentGroups as $groupId => $attachmentGroup)
        <div class="border-gray-300 border-2 rounded p-0.5 m-0.5 {{$width}}">
            {{-- {{$width}} {{$photoPerColumn}} --}}
            <div class="font-bold text-center text-sm" title="#{{$groupId}}">{{$attachmentGroup['name']}}</div>
            <x-renderer.attachment2a 
                name={{$name}} 
                :value="$attachmentGroup['items']" 
                :properties="$properties" 
                readOnly={{$readOnly}} 
                groupMode="{{count($attachmentGroups) > 1}}"
                groupId="{{$groupId}}"
                openType="{{$openType}}"
                photoPerColumn="{{$photoPerColumn}}"
                />
        </div>
    @endforeach
</div>

@if(!$readOnly)
    <input id="{{$name}}-toBeDeleted" 
        name="{{$name}}[toBeDeleted]" 
        readonly 
        placeholder="To be deleted IDs"
        type='{{$hiddenOrText}}' 
        class='p-2.5 w-full bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray '
        >
@endif