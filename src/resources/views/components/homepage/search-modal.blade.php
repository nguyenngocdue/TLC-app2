<button class="relative align-middle rounded-md focus:outline-none focus:shadow-outline-purple" @click="toggleSearchMenu" @keydown.escape="closeSearchMenu" aria-label="Search" aria-haspopup="true">
    <i class="fa-solid fa-magnifying-glass"></i>
</button>
  <template x-if="isSearchMenuOpen">
    <div tabindex="-1" class="fixed top-0 left-0 right-0 z-50 w-full p-4 md:h-full" @click.away="closeSearchMenu" @keydown.escape="closeSearchMenu">
        <div class="relative top-10 px-96 w-full h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <div class="flex">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white">
                            Tell me what you want to do
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="large-modal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <input type="search" id="search" data-search class="block w-full p-4 pl-5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search..." required>
                </div>
                <!-- Modal body -->
                <div class="px-6 space-y-6 overflow-y-auto h-96" data-container>
                    <template data-search-template>
                        <div>
                                <p class="py-1 text-base font-semibold text-gray-500 dark:text-gray-400" data-sub-package></p>
                                <ul class="my-4 space-y-3">
                                    <li>
                                    <a href="#" class="flex items-center p-3 text-base font-normal text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white" data-href>
                                        <i class="fa-thin fa-arrow-right"></i>
                                        <span class="flex-1 ml-3 whitespace-nowrap" data-app></span>
                                        <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-medium text-gray-500 bg-gray-200 rounded dark:bg-gray-700 dark:text-gray-400" data-package></span>
                                    </a>
                                    </li>
                                </ul>
                        </div>

                    </template>
                </div>

                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <a href="#" class="inline-flex items-center text-xs font-normal text-gray-500 hover:underline dark:text-gray-400">
                        <svg aria-hidden="true" class="w-3 h-3 mr-2" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z"></path></svg>
                        Didn't find what you were looking for?</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        const dataSearchTemplate = document.querySelector("[data-search-template]")
        const dataContainer = document.querySelector("[data-container]")
        const searchInput = document.querySelector("[data-search]")
        const allApps = (@json($allApps))

        let apps = [];
        searchInput.addEventListener("input",(e)=>{
            const value = e.target.value.toLowerCase();
            apps.forEach(app => {
                matchRegex(value,app);
                const isVisible = app.title.toLowerCase().includes(value) || app.name.toLowerCase().includes(value) || app.package.toLowerCase().includes(value) || app.sub_package.toLowerCase().includes(value)
                app.element.classList.toggle("hidden",!isVisible)
            })
            })
        apps = allApps.map(item => {
            const resultNode = dataSearchTemplate.content.cloneNode(true).children[0];
            const subPackage = resultNode.querySelector("[data-sub-package]");
            const elementHref = resultNode.querySelector("[data-href]")
            const package =resultNode.querySelector("[data-package]")
            const appName = resultNode.querySelector("[data-app]")
            subPackage.textContent = capitalize(item.sub_package)
            elementHref.href = item.href
            package.textContent = capitalize(item.package)
            appName.textContent = item.title
            dataContainer.append(resultNode)
            return { name: item.name , package : item.package , sub_package : item.sub_package , title : item.title, element: resultNode}
        })
        function matchRegex(valueSearch,app){
            console.log(valueSearch);
            const formatText = valueSearch.replaceAll(" ",".*");
            console.log(app);
            const regex = /+formatText+/gm;
            const arr = [app.title,app.sub_package,app.package,app.name]
            const str = arr.join(" ") ;
            console.log(regex);
            console.log(regex.exec(str)); 
            // let m;
            // while ((m = regex.exec(str)) !== null) {
            //     // This is necessary to avoid infinite loops with zero-width matches
            //     if (m.index === regex.lastIndex) {
            //         regex.lastIndex++;
            //     }
                
            //     // The result can be accessed through the `m`-variable.
            //     m.forEach((match, groupIndex) => {
            //         // console.log(`Found match, group ${groupIndex}: ${match}`);
            //     });
            // }
        }
        
        function capitalize(str){
            const arr = str.split("_");
                for (var i = 0; i < arr.length; i++) {
                    arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1);
                }
            const str2 = arr.join(" ");
            return str2;
        }
    </script>
  </template>
  
  