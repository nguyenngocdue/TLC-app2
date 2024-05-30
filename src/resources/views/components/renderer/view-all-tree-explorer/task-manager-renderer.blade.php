<x-renderer.card title="Tasks">
    {{-- @dump($tasks) --}}
    {{-- @dump($dataSource) --}}
    <x-renderer.table 
        groupBy="phase"
        groupByLength="255"
        :columns="$columns" 
        :dataSource="$dataSource" 

        showNo=1
        />
</x-renderer.card>

<x-renderer.card title="Members">
    <div class="flex gap-1 rounded">
        @foreach($users as $user)
            @php
                if($user->getAvatar){
                    $src = $minioPath.$user->getAvatar->url_thumbnail;
                } else {
                    $src = "/images/avatar.jpg";
                }
            @endphp
            <div class='h-24'>
                <div class="w-20 h-20 text-center" title="{{$user->name}} (#{{$user->id}})">
                    <img class='rounded-full' src="{{$src}}"></img>
                    {{$user->first_name}}
                </div>
            </div>
        @endforeach
    </div>
</x-renderer.card>

