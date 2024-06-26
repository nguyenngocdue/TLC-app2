@if($isRender)
<button class="focus:shadow-outline-purple rounded-md focus:outline-none" @click="toggleProjectMenu" @keydown.escape="closeProjectMenu">
    {{-- <i class="fa-solid fa-city"></i>
    <span class="font-semibold">{{$selectedProjectName}}</span> --}}
    <x-renderer.avatar-item 
            title="{{$selectedProject->name}}"
            {{-- description="{{$selectedProject->description}}" --}}
            avatar="{{$selectedProject->thumbnail_url}}"
            shape="round"
            />
        </button>
</button>
@endif
@php
   
@endphp
<template x-if="isProjectMenuOpen">
    <ul x-transition:leave="transition ease-in duration-150" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        @click.away="closeProjectMenu" 
        @keydown.escape="closeProfileMenu" 
        class="absolute right-52 top-10 p-1 spa1ce-y-2 overflow-y-auto max-h-[40rem] text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-600 dark:text-gray-300 dark:bg-gray-700" aria-label="submenu">
        
        <x-homepage.menu-project-item route="{{$route}}" :project='$allProject' selectedProjectId="{{$selectedProjectId}}"></x-homepage.menu-project-item>
        @if(!$recent->isEmpty())
        <p class="p-2 text-sm font-medium text-gray-900 dark:text-gray-300">Recent Projects</p>
        @endempty
        @foreach($recent as $project)
            <x-homepage.menu-project-item route="{{$route}}" :project='$project' selectedProjectId="{{$selectedProjectId}}"></x-homepage.menu-project-item>
        @endforeach
        @if(!$projects->isEmpty())
            <p class="border-t p-2 text-sm font-medium text-gray-900 dark:text-gray-300">Projects</p>
        @endempty
        @foreach($projects as $project)
            <x-homepage.menu-project-item route="{{$route}}" :project='$project' selectedProjectId="{{$selectedProjectId}}"></x-homepage.menu-project-item>
        @endforeach

    </ul>
</template>