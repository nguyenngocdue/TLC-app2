<x-renderer.card title="Bookmarks" icon="fa-duotone fa-bookmark">
    <div class="flex">
        <div id="list-bookmark" class="gap-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 2xl:grid-cols-10">
            
        </div>
        <div id="button-show-more">
            
        </div>
    </div>
</x-renderer.card>
<script>
    var allAppsBookmark = @json($allAppsBookmark);
    allAppsBookmark = Object.values(allAppsBookmark);
    classList = "p-1 border border-gray-200 text-left 1w-40 rounded text-blue-500 hover:text-gray-200 hover:bg-blue-500";
    listBookmark = $(`#list-bookmark`);
    buttonShowMore = $(`#button-show-more`);
    renderBookmarkHtml(allAppsBookmark,listBookmark);
    renderButtonShowMore(allAppsBookmark,buttonShowMore);
    function renderBookmarkHtml(allAppsBookmark,listBookmark){
        let resultHtmlListBookmark = ``;
        for (let i = 0; i < allAppsBookmark.length; i++) {
            value = allAppsBookmark[i];
            if(i >= 10){
                resultHtmlListBookmark += `<a id="${value.name}" href="${value.href}" title="${value.title}" class="${classList} toggle-bookmark hidden">
                ${value.title}</a>`
            }else{
                resultHtmlListBookmark += `<a id="${value.name}" href="${value.href}" title="${value.title}" class="${classList} block">
                ${value.title}</a>`
            }
        }
        listBookmark.html(resultHtmlListBookmark);
    }
    function renderButtonShowMore(allAppsBookmark,buttonShowMore){
        if(allAppsBookmark.length > 10){
            buttonShowMore.html(`
            <button type="button" id='toggle_more' class="flex justify-end mb-auto ml-1 px-2 py-1 border rounded-md text-blue-600 hover:bg-gray-200" @click="toggleMore()">
                <i class="fa-solid fa-chevron-down"></i>
            </button>`)
        }
    }
    function toggleMore(){
        $('.toggle-bookmark').toggle();
        if($('.toggle-bookmark').is(':visible')){
            $('#toggle_more').html('<i class="fa-solid fa-chevron-up"></i>');
        }else{
            $('#toggle_more').html('<i class="fa-solid fa-chevron-down"></i>');
        }
    }
</script>