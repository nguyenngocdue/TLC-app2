<button class="relative align-middle rounded-md focus:outline-none focus:shadow-outline-purple" @click="toggleNotificationsMenu" @keydown.escape="closeNotificationsMenu" aria-label="Notifications" aria-haspopup="true">
    <i class="fa-duotone fa-bell"></i>
    @php
        $countUnreadNotifications = count($unreadNotifications);
        if($countUnreadNotifications > 99) $countUnreadNotifications = '99+';
    @endphp
    @if($countUnreadNotifications != 0)
    <div aria-hidden="true" class="absolute top-0 right-0 inline-block w-5 h-5 transform translate-x-3 -translate-y-2 bg-red-600 border-2 items-center justify-center border-white rounded-full dark:border-gray-800">
        <p id="countUread" class="text-gray-100 text-[8px] mt-[3px]">{{$countUnreadNotifications}}</p>
    </div>
    @endif
</button>
<script type="text/javascript">
    window.Echo.channel('notifications')
       .notification((data) => {
        $.ajax({    
            method: "GET",
            url: "/system/notifications",
            dataType: "json",
            success: (response) => {
                var unreadNotifications = response['meta']['unread'];
                var count = unreadNotifications.length;
                console.log(count);
                document.getElementById("countUread").innerHTML = count;
            },
            error: (error) => {
                console.log(error);
            }
        });
       });
</script>
@once
<script type="text/javascript">
const initTab = (tabId) => {
    let tabsContainer = document.querySelector("#tabs-" + tabId);
    let tabTogglers = tabsContainer.querySelectorAll("#tabs-" + tabId + " a");
    tabTogglers.forEach(function(toggler) {
        toggler.addEventListener("click", function(e) {
            e.preventDefault();
            let tabName = this.getAttribute("href");
            let tabContents = document.querySelector("#tab-contents-" + tabId);
            for (let i = 0; i < tabContents.children.length; i++) {
                tabTogglers[i].parentElement.classList.remove("border-t", "border-r", "border-l", "-mb-px", "bg-white");
                tabContents.children[i].classList.remove("hidden");
                if ("#" + tabContents.children[i].id === tabName) continue;
                tabContents.children[i].classList.add("hidden");
            }
            e.target.parentElement.classList.add("border-t", "border-r", "border-l", "-mb-px", "bg-white");
        });
    });
}
</script>
@endonce
<template x-if="isNotificationsMenuOpen">
    <div x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.away="closeNotificationsMenu" @keydown.escape="closeNotificationsMenu" class="absolute right-0 w-[500px] h-[calc(100vh-100px)] overflow-y-auto text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
            <div>
                <div class="flex justify-between">
                    <h1 class="p-3 text-base font-semibold">My Notifications</h1>
                    <a href="{{route('notifications.index')}}" class="m-3 text-xs font-semibold text-blue-500 hover:text-blue-800">Show all</a>
                </div>
                <div class="flex">
                    <ul id="tabs-notificationsall123456789" class="inline-flex px-1 w-full border-b text-base">
                        <li class="px-3 text-gray-800 font-semibold py-1 rounded-t bg-white border-t border-r border-l -mb-px">
                            <a href="#allNotifications">All</a>
                        </li>
                        <li class="px-3 text-gray-800 font-semibold py-1 rounded-t ">
                            <a href="#unreadNotifications">Unread</a>
                        </li>
                    </ul>
                </div>
                <div id="tab-contents-notificationsall123456789">
                    <x-renderer.loading />
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
        $.ajax({
            method: "GET",
            url: "/system/notificationsRender",
            dataType: "json",
            success: (response) => {
                var component = response['hits'];
                var htmtAtribute =document.getElementById("tab-contents-notificationsall123456789");
                htmtAtribute.innerHTML = component
            },
            error: (error) => {
                console.log(error);
            }
        });
    })
    </script>
    
    <script>
        initTab('notificationsall123456789');
    </script>
</template>


