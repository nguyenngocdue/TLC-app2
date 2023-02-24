<button class="relative align-middle rounded-md focus:outline-none focus:shadow-outline-purple" @click="toggleNotificationsMenu" @keydown.escape="closeNotificationsMenu" aria-label="Notifications" aria-haspopup="true">
    <i class="fa-duotone fa-bell"></i>
    <span aria-hidden="true" class="absolute top-0 right-0 inline-block w-3 h-3 transform translate-x-1 -translate-y-1 bg-red-600 border-2 border-white rounded-full dark:border-gray-800"></span>
</button>
<template x-if="isNotificationsMenuOpen">
    <div x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.away="closeNotificationsMenu" @keydown.escape="closeNotificationsMenu" class="absolute right-0 w-[500px] text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700">
            <div>
                <h1 class="p-3 text-2xl font-semibold">Notifications</h1>
                
                <div class="flex">
                    <ul id="tabs-notificationsall123456789" class="inline-flex px-1 w-full border-b text-base">
                        <li class="px-3 text-gray-800 font-semibold py-1 rounded-t bg-white border-t border-r border-l -mb-px"><a href="#allNotifications">All</a></li>
                        <li class="px-3 text-gray-800 font-semibold py-1 rounded-t "><a href="#unreadNotifications">Unread</a></li>
                    </ul>
                </div>
                <div id="tab-contents-notificationsall123456789">
                    <div id="allNotifications" class="p-2">
                        <x-renderer.all-notifications :dataSource="$notifications" />
                </div>
                    <div id="unreadNotifications" class="p-2 hidden">
                        <x-renderer.all-notifications :dataSource="$unreadNotifications" />
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <script>
        initTab('notificationsall123456789');
    </script>
</template>


