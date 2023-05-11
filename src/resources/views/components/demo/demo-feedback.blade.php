<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Alerts">
        <x-feedback.alert type="success" message="Hello Success"></x-feedback.alert>
        <x-feedback.alert type="info" message="Hello Info"></x-feedback.alert>
        <x-feedback.alert type="warning" message="Hello Warning"></x-feedback.alert>
        <x-feedback.alert type="error" message="Hello Error"></x-feedback.alert>
        <br />
        <br />
        Empty attributes:
        <x-feedback.alert />
    </x-renderer.card>
    <x-renderer.card title="Progress Bars">
        <x-renderer.progress-bar :dataSource="$dataSourceProgressBar"/>
    </x-renderer.card>
    <x-renderer.card title="Modals">
        <div class="mb-8 grid gap-6">
            <x-renderer.button type="success" 
                click="openModal('modal-empty-123')"
                keydown="closeModal('modal-empty-123')"
                >Open An Empty Modal </x-renderer.button>
            <x-modals.modal-empty modalId="modal-empty-123" />
        </div>
    </x-renderer.card>
    <x-renderer.card title="Popover">
        <button data-popover-target="popover-1" data-popover-placement="top" type="button" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Button 1</button>
        <button data-popover-target="popover-2" data-popover-placement="top-end" type="button" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Button 2</button>
        <button data-popover-target="popover-3" data-popover-placement="bottom" type="button" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Button 3</button>
        <button data-popover-target="popover-4" data-popover-placement="bottom-end" type="button" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Button 4</button>
        <button data-popover-target="popover-5" type="button" class=" text-white bg-green-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Button 5</button>
        <button data-popover-target="popover-6" type="button" class=" text-white bg-pink-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-pink-600 dark:hover:bg-pink-700 dark:focus:ring-pink-800">Button 6</button>
        @php
        $content1 ='<div class="px-3 py-2">
            <p>And heres some amazing content. Its very engaging. Right?</p>
        </div>';
        $content2 ='<div class="p-3">
            <div class="flex items-center justify-between mb-2">
                <a href="#">
                    <img class="w-10 h-10 rounded-full" src="/images/helen.jpeg" alt="Jese Leos">
                </a>
                <div>
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Follow</button>
                </div>
            </div>
            <p class="text-base font-semibold leading-none text-gray-900 dark:text-white">
                <a href="#">Jese Leos</a>
            </p>
            <p class="mb-3 text-sm font-normal">
                <a href="#" class="hover:underline">@jeseleos</a>
            </p>
            <p class="mb-4 text-sm font-light">Open-source contributor. Building <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline">flowbite.com</a>.</p>
            <ul class="flex text-sm font-light">
                <li class="mr-2">
                    <a href="#" class="hover:underline">
                        <span class="font-semibold text-gray-900 dark:text-white">799</span>
                        <span>Following</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="hover:underline">
                        <span class="font-semibold text-gray-900 dark:text-white">3,758</span>
                        <span>Followers</span>
                    </a>
                </li>
            </ul>
        </div>';
        $content3 = '<div class="p-3">
            <div class="flex">
                <div class="mr-3 shrink-0">
                    <a href="#" class="block p-2 bg-gray-100 rounded-lg dark:bg-gray-700">
                        <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/logo.svg" alt="Flowbite logo">
                    </a>
                </div>
                <div>
                    <p class="mb-1 text-base font-semibold leading-none text-gray-900 dark:text-white">
                        <a href="#" class="hover:underline">Flowbite</a>
                    </p>
                    <p class="mb-3 text-sm font-normal">
                        Tech company
                    </p>
                    <p class="mb-4 text-sm font-light">Open-source library of Tailwind CSS components and Figma design system.</p>
                    <ul class="text-sm font-light">
                        <li class="flex items-center mb-2">
                            <span class="mr-1 font-semibold text-gray-400">
                                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline">https://flowbite.com/</a>
                        </li>
                        <li class="flex items-start mb-2">
                            <span class="mr-1 font-semibold text-gray-400">
                                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <span>4,567,346 people like this including 5 of your friends</span>
                        </li>
                    </ul>
                    <div class="flex mb-3 -space-x-3">
                        <img class="w-8 h-8 border-2 border-white rounded-full dark:border-gray-800" src="/images/helen.jpeg" alt="">
                        <img class="w-8 h-8 border-2 border-white rounded-full dark:border-gray-800" src="/images/helen.jpeg" alt="">
                        <img class="w-8 h-8 border-2 border-white rounded-full dark:border-gray-800" src="/images/helen.jpeg" alt="">
                        <a class="flex items-center justify-center w-8 h-8 text-xs font-medium text-white bg-gray-400 border-2 border-white rounded-full hover:bg-gray-500 dark:border-gray-800" href="#">+3</a>
                    </div>
                    <div class="flex">
                        <button type="button" class="inline-flex items-center justify-center w-full px-5 py-2 mr-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"><svg class="w-4 h-4 mr-2" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>Like page</button>
                        <button class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg shrink-0 focus:outline-none hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700" type="button">
                            <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>';
        @endphp
        <x-renderer.popover id="popover-1" title="Popover Title" :content="$content1" />
        <x-renderer.popover id="popover-2" title="Popover Title" :content="$content1" />
        <x-renderer.popover id="popover-3" title="Popover Title" :content="$content1" />
        <x-renderer.popover id="popover-4" title="Popover Title" :content="$content1" />
        <x-renderer.popover id="popover-5" :content="$content2" />
        <x-renderer.popover id="popover-6" :content="$content3" />

    </x-renderer.card>
</div>
