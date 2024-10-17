
<button class="focus:shadow-outline-purple rounded-md focus:outline-none" @click="toggleProjectDropdown" @keydown.escape="closeProjectDropdown">
    {{-- <i class="fa-solid fa-city"></i>
    <span class="font-semibold">{{$selectedProjectName}}</span> --}}
    <x-renderer.avatar-item 
            title="{{$selectedProject?->name}}"
            {{-- description="{{$selectedProject->description}}" --}}
            avatar="{{$selectedProject?->src}}"
            shape="round"
            />
        </button>
</button>

<template x-if="isProjectDropdownOpen">
    <ul x-transition:leave="transition ease-in duration-150" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        @click.away="closeProjectDropdown" 
        @keydown.escape="closeProjectDropdown" 
        class="absolute right-52 top-10 p-1 spa1ce-y-2 overflow-y-auto max-h-[40rem] text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-600 dark:text-gray-300 dark:bg-gray-700" aria-label="submenu">
        
        @foreach($projects as $project)
            <x-homepage.menu-project-dropdown-item 
                route="{{$route}}" 
                :project='$project' 
                selectedProjectId="{{$selectedProject?->id}}"
                />
        @endforeach

    </ul>
</template>