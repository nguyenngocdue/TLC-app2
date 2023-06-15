<div>
    @forelse($users as $user)
    @php
        $value =  $dataSource[$user->id] ?? [];
    @endphp
    <div class="border focus:outline-none bg-gray-200 hover:bg-gray-300 my-1 p-1 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
        <a href="{{$value['href']}}" class="flex">
            <x-renderer.avatar-user>{!!$user!!}</x-renderer.avatar-user>
            <x-renderer.tag>{{$value['total_pending_approval']}}</x-renderer.tag>
        </a>
    </div>
        @while($condition)
            
        @endwhile
    @empty
        
    @endforelse
</div>