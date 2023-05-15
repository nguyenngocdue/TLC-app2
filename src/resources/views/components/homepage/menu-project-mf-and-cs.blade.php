<button class="focus:shadow-outline-purple rounded-md focus:outline-none" @click="toggleProjectMenu" @keydown.escape="closeProjectMenu">
    <i class="fa-solid fa-city"></i>
</button>
<template x-if="isProjectMenuOpen">
    <ul x-transition:leave="transition ease-in duration-150" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        @click.away="closeProjectMenu" 
        @keydown.escape="closeProfileMenu" 
        class="absolute right-52 top-10 p-1 spa1ce-y-2 overflow-y-auto max-h-[40rem] text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-600 dark:text-gray-300 dark:bg-gray-700" aria-label="submenu">
        @foreach($projects as $project)
        <li class="flex w-32">
            <a href="{{$project->href}}" 
                title="{{$project->description}}"
                class="inline-flex items-center w-full p-1 space-x-2 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                <x-renderer.image spanClass="w-10 h-10" src="{{$project->getAvatarThumbnailUrl('/images/modules.png')}}" ></x-renderer.image>
                <span>{{$project->name}}</span>
            </a>
        </li>
        @endforeach

    </ul>
</template>