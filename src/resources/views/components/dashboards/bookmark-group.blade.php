<x-renderer.card title="Bookmarks">
    <div class="flex">
        <div class="grid grid-cols-3 gap-2 md:grid-cols-4 lg:grid-cols-6 md:gap-4 2xl:grid-cols-10 items-center">
            @foreach($arrayBookmarkFirst as $value)
            <a href="{{$value['href']}}" title="{{$value['title']}}" class="block text-center w-40 rounded-md text-blue-500 hover:text-blue-700 ">
                {{$value['title']}} </a>
            @endforeach
            @foreach($arrayBookmarkSecond as $value)
            <a href="{{$value['href']}}" title="{{$value['title']}}" class="toogle-bookmark text-center w-40 hidden text-blue-500 hover:text-blue-700">
                {{$value['title']}} </a>
            @endforeach
        </div>
        @if($isShowMore)
            <button type="button" id='toogle_more' class="flex justify-end mb-auto ml-auto px-2 border rounded-md text-blue-600 hover:bg-gray-200" @click="toogleMore()">
                <i class="fa-solid fa-chevron-down"></i>
            </button>
        
        @endif
    </div>
</x-renderer.card>
<script>
    function toogleMore(){
        $('.toogle-bookmark').toggle();
        if($('.toogle-bookmark').is(':visible')){
            $('#toogle_more').html('<i class="fa-solid fa-chevron-up"></i>');
        }else{
            $('#toogle_more').html('<i class="fa-solid fa-chevron-down"></i>');
        }
    }
</script>