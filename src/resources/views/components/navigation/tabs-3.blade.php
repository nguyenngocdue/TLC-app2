<div class="rounded border w-1/2 mx-auto mt-4">
    <!-- Tabs -->
    <ul id="tabs" class="inline-flex pt-2 px-1 w-full border-b">
        <li class="bg-white px-4 text-gray-800 font-semibold py-2 rounded-t border-t border-r border-l -mb-px"><a id="default-tab" href="#first">Tab 1</a></li>
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t"><a href="#second">Tab 2</a></li>
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t"><a href="#third">Tab 3</a></li>
        <li class="px-4 text-gray-800 font-semibold py-2 rounded-t"><a href="#fourth">Tab 4</a></li>
    </ul>

    <!-- Tab Contents -->
    <div id="tab-contents">
        <div id="first" class="p-4">
            First tab
        </div>
        <div id="second" class="hidden p-4">
            Second tab
        </div>
        <div id="third" class="hidden p-4">
            Third tab
        </div>
        <div id="fourth" class="hidden p-4">
            Fourth tab
        </div>
    </div>
</div>


<script type="text/javascript">
    let tabsContainer = document.querySelector("#tabs");

    let tabTogglers = tabsContainer.querySelectorAll("#tabs a");

    console.log(tabTogglers);

    tabTogglers.forEach(function(toggler) {
        toggler.addEventListener("click", function(e) {
            e.preventDefault();

            let tabName = this.getAttribute("href");

            let tabContents = document.querySelector("#tab-contents");

            for (let i = 0; i < tabContents.children.length; i++) {

                tabTogglers[i].parentElement.classList.remove("border-t", "border-r", "border-l", "-mb-px", "bg-white");
                tabContents.children[i].classList.remove("hidden");
                if ("#" + tabContents.children[i].id === tabName) {
                    continue;
                }
                tabContents.children[i].classList.add("hidden");

            }
            e.target.parentElement.classList.add("border-t", "border-r", "border-l", "-mb-px", "bg-white");
        });
    });

</script>
