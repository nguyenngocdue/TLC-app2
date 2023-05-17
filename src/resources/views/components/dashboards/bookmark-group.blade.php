@php $classList = "p-1 border border-gray-200 text-left w-40 rounded text-blue-500 hover:text-gray-200 hover:bg-blue-500" @endphp
<x-renderer.card title="Bookmarks" icon="fa-duotone fa-bookmark">
    <div class="flex">
        <div id="list-bookmark" class="grid grid-cols-3 gap-2 md:grid-cols-4 lg:grid-cols-6 md:gap-4 2xl:grid-cols-10">
            @foreach($arrayBookmarkFirst as $value)
            <a id="{{$value['name']}}" href="{{$value['href']}}" title="{{$value['title']}}" class="{{$classList}} block">
                {{$value['title']}} </a>
            @endforeach
            @foreach($arrayBookmarkSecond as $value)
            <a id="{{$value['name']}}" href="{{$value['href']}}" title="{{$value['title']}}" class="{{$classList}} toggle-bookmark hidden">
                {{$value['title']}} </a>
            @endforeach
        </div>
        @if($isShowMore)
            <button type="button" id='toggle_more' class="flex justify-end mb-auto ml-auto px-2 border rounded-md text-blue-600 hover:bg-gray-200" @click="toggleMore()">
                <i class="fa-solid fa-chevron-down"></i>
            </button>
        @endif
    </div>
</x-renderer.card>
<script>
    function toggleMore(){
        $('.toggle-bookmark').toggle();
        if($('.toggle-bookmark').is(':visible')){
            $('#toggle_more').html('<i class="fa-solid fa-chevron-up"></i>');
        }else{
            $('#toggle_more').html('<i class="fa-solid fa-chevron-down"></i>');
        }
    }
</script>