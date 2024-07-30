<x-renderer.card title="Bookmarks" icon="fa-duotone fa-bookmark">
    <div class="flex justify-center">
        <div id="bookmark-list" class="gap-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6"></div>
        <div id="bookmark-btn-more">            
            <button 
                id="toggle_more" 
                type="button" 
                class="justify-end mb-auto ml-1 px-2 py-1 border rounded-md text-blue-600 hover:bg-gray-100"
                onclick="toggleMore()"
                >
                <i class="fa-solid fa-chevron-down"></i>
            </button>
        </div>
    </div>
</x-renderer.card>

<script>
    function toggleMore(){
        $('.toggle-bookmark').each(function() {
            $(this).css('display', function(i, display) {
                return display === 'flex' ? 'none' : 'flex';
            });
        });
        if($('.toggle-bookmark').is(':visible')){
            $('#toggle_more').html('<i class="fa-solid fa-chevron-up"></i>');
        }else{
            $('#toggle_more').html('<i class="fa-solid fa-chevron-down"></i>');
        }
    }

    function BookmarkBarInit(){
        document.getElementById("bookmark-list").innerHTML = "";
        const bookmarkItems = []
        Object.keys(allApps).forEach(function(key) {
            var items = allApps[key]['items'];
            bookmarkItems.push(...items.filter(item => item.bookmarked == true))
        })
        if(bookmarkItems.length >=12) {
            document.getElementById("toggle_more").style.display = "flex";
        } else {
            document.getElementById("toggle_more").style.display = "none";
        }
        if(bookmarkItems.length == 0) {
            document.getElementById("bookmark-list").innerHTML = "<div class='text-gray-500 col-span-12'><i class='fa-duotone fa-circle-question px-2'></i><span>Hint: Use bookmark button to add your favourite items to this section.</span></div>";
            return;
        }
        bookmarkItems.sort((a,b) => b.click_count - a.click_count)

        bookmarkItems.forEach(function(item, index){
            var { href, icon, title, click_count } = item
            var bookmark = document.createElement("a");
            bookmark.href = href;
            bookmark.className = "flex items-center p-2 bg-white rounded border cursor-pointer text-blue-500 hover:bg-blue-500 hover:text-gray-100 hover:shadow-lg min-h-16";
            if(index >= 12) bookmark.className += " hidden toggle-bookmark";
            let result = ''
            result += `<span class="px-3 text-4xl"> ${icon}</span>`
            result += ` ${title}`
            // result += ` (${click_count})`
            bookmark.innerHTML = result;
            document.getElementById("bookmark-list").appendChild(bookmark);
        });
    }
    BookmarkBarInit();
</script>