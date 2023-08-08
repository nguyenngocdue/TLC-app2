@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')

<div class="px-4 bg-white">
    @if(app()->isProduction())
    <x-elapse title="Boot the layout: "/>
    
    <x-dashboards.bookmark-group />
    <x-elapse title="Bookmark group: " />

    <x-renderer.project.project-overview table="projects" />
    <x-elapse title="Project Overview: " />

    <x-dashboards.my-view-groups />
    <x-elapse title="My View: "/>
     {{-- <x-dashboards.widget-groups /> --}}
    {{-- <x-elapse title="Widget group: "/> --}}
    @else
    <div class="flex justify-center h-screen overflow-y-scroll">
        <x-social.sidebar >
            <x-social.menu-item-list />
            <hr/>
            <x-social.menu-item-list title="Your Shortcuts"/>
        </x-social.sidebar>
        <section class="content-container bg-white w-full lg:w-2/3 xl:w-2/5 pt-32 lg:pt-16 px-2">
            <x-social.post-form />
            <x-social.card>
                <div class="flex items-center justify-between px-4 py-2">
                    <div class="post-avatar-user flex space-x-2 items-center">
                        <div class="relative">
                            <img
                                src="{{asset('images/avatar.jpg')}}"
                                alt="Profile picture"
                                class="w-10 h-10 rounded-full cursor-pointer"
                            />
                            <span class="bg-green-500 w-3 h-3 rounded-full absolute right-0 top-3/4 border-white border-2"></span>
                        </div>
                        <div>
                            <div class="font-semibold cursor-pointer">
                                Foden Ngo
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                an hours ago
                            </span>
                        </div>
                    </div>
                    <div class="w-8 h-8 grid place-items-center text-xl text-gray-500 hover:bg-gray-200 dark:text-dark-txt dark:hover:bg-dark-third rounded-full cursor-pointer">
                        <i class="bx bx-dots-horizontal-rounded"></i>
                    </div>
                </div>
                <div className="post-captions text-justify px-4 py-2">Captions</div>
                <div className="flex justify-center py-2 m-auto">
                    <img src="https://minio.tlcmodular.com/tlc-app/dev-canh/2023/08/73d9675d3201e15fb810-1.jpg" 
                    alt="Post image" class="w-full h-96 object-cover rounded-lg"/>
                </div>
                <div class="flex justify-between mt-1">
                    <div class="flex space-x-2 text-sm text-gray-500">
                        <i class="fa-thin fa-thumbs-up"></i>
                        <i class="fa-thin fa-heart"></i>
                        <p>107</p>
                    </div>
                    <div class="flex space-x-2 text-sm text-gray-500">
                        <p>18 comments</p>
                        <p>10 shares</p>
                    </div>

                </div>
                <div class="flex border-y">
                    <x-social.button-icon width="w-1/3" textColor="text-gray-500" icon="fa-thin fa-thumbs-up" title="Like" widthIcon="w-6" heightIcon="h-6"/>
                    <x-social.button-icon width="w-1/3" textColor="text-gray-500" icon="fa-thin fa-comment" title="Comment" widthIcon="w-6" heightIcon="h-6"/>
                    <x-social.button-icon width="w-1/3" textColor="text-gray-500" icon="fa-thin fa-share-nodes" title="Share" widthIcon="w-6" heightIcon="h-6"/>
                </div>
                <div class="flex mt-2 component-comment">
                    <img src="{{asset('images/avatar.jpg')}}" alt="Profile picture" class="w-10 h-10 mt-2 rounded-full">
                    <div class="bg-gray-200 w-full rounded-lg py-2 px-3 ml-2">
                        <textarea type="text" class="block w-full p-4 pl-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write comment ..."></textarea>
                        <div class="flex justify-between">
                            <div class="space-x-1">
                                <button>
                                    <i class="fa-thin fa-face-smile"></i>
                                </button>
                                <button>
                                    <i class="fa-thin fa-camera"></i>
                                </button>
                            </div>
                            <div>
                                <button>
                                    <i class="fa-solid fa-paper-plane-top"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list-comment py-2 px-4  h-64 overflow-y-scroll scrollbar-thin scrollbar-thumb-slate-300">
                    <div class="flex space-x-2">
                        <img src="{{asset('images/avatar.jpg')}}" alt="Profile picture" class="w-10 h-10 mt-2 rounded-full">
                        <div>
                            <div class="bg-gray-100 dark:bg-gray-800 p-2 rounded-2xl text-sm">
                                <span class="font-semibold block">Foden Ngo</span>
                                <span>Comment</span>
                            </div>
                            <div class="flex text-xs text-gray-500 space-x-2 ml-3 mb-2">
                                <div>Like</div>
                                <div>Feedback</div>
                                <div>1 hours</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex component-comment ml-10 mb-2">
                        <img src="{{asset('images/avatar.jpg')}}" alt="Profile picture" class="w-8 h-8 mt-2 rounded-full">
                        <div class="bg-gray-200 w-full rounded-lg py-1 px-2 ml-2">
                            <textarea type="text" class="block w-full p-4 pl-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write comment ..."></textarea>
                            <div class="flex justify-between">
                                <div class="space-x-1">
                                    <button>
                                        <i class="fa-thin fa-face-smile"></i>
                                    </button>
                                    <button>
                                        <i class="fa-thin fa-camera"></i>
                                    </button>
                                </div>
                                <div>
                                    <button>
                                        <i class="fa-solid fa-paper-plane-top"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <img src="{{asset('images/avatar.jpg')}}" alt="Profile picture" class="w-10 h-10 mt-2 rounded-full">
                        <div>
                            <div class="bg-gray-100 dark:bg-gray-800 p-2 rounded-2xl text-sm">
                                <span class="font-semibold block">Foden Ngo</span>
                                <span>Comment abcasdalfalfashflahlflafhasflfalfhaflhalfalfalfhaflaf haslfssssssssssssssssssssssssssssss sssssssss</span>
                            </div>
                            <div class="flex text-xs text-gray-500 space-x-2 ml-3 mb-2">
                                <div>Like</div>
                                <div>Feedback</div>
                                <div>1 hours</div>
                            </div>
                        </div>

                    </div>
                    <div class="flex space-x-2">
                        <img src="{{asset('images/avatar.jpg')}}" alt="Profile picture" class="w-10 h-10 mt-2 rounded-full">
                        <div>
                            <div class="bg-gray-100 dark:bg-gray-800 p-2 rounded-2xl text-sm">
                                <span class="font-semibold block">Foden Ngo</span>
                                <span>Comment</span>
                            </div>
                            <div class="flex text-xs text-gray-500 space-x-2 ml-3 mb-2">
                                <div>Like</div>
                                <div>Feedback</div>
                                <div>1 hours</div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-social.card>
        </section>
        <x-social.sidebar right="true">
            {{-- <x-elapse title="Boot the layout: "/>
            <x-dashboards.bookmark-group />
            <x-elapse title="Bookmark group: " />
            <x-renderer.project.project-overview table="projects" />
            <x-elapse title="Project Overview: " />
            <x-dashboards.my-view-groups />
            <x-elapse title="My View: "/> --}}
        </x-social.sidebar>
    </div>
    @endif
</div>
    <script>
        tinymce.init({
          selector: "#post-textarea",
          plugins: "emoticons",
          toolbar: "emoticons",
          toolbar_location: "bottom",
          menubar: false
        });
      </script>

@endsection