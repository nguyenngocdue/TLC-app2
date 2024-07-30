<button class="relative align-middle rounded-md focus:outline-none focus:shadow-outline-purple" 
    @click="toggleModal('{{$modalId}}')" 
    @keydown.escape="closeModal('{{$modalId}}')" 
    accesskey="q" 
    aria-label="Search" 
    aria-haspopup="true">
    <i class="fa-solid fa-magnifying-glass"></i>
</button>

@extends('modals.modal-large')
@section($modalId.'-header')
<h3 class="text-sm font-medium text-gray-900 dark:text-white">Tell me what you want to do</h3>
@endsection

@section($modalId.'-header-extra')
<input type="search" autocomplete="off" id="search" data-search class="block w-full p-3 pl-5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search..." required>
@endsection

@section($modalId.'-footer')
<div class="flex items-center p-4 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
    <div class="inline-flex items-center text-xs font-normal text-gray-500 hover:underline dark:text-gray-300">
        <i class="fa-light fa-circle-question pr-1"></i>
        <p class="mr-1">Total number of apps: <p class="font-bold">{{" ".$totalApps}}</p></p>
        
    </div>
</div>
@endsection

@section($modalId.'-javascript')
<script>
    dataContainer = document.querySelector("[data-container]")
    searchInput = document.querySelector("[data-search]")
    searchInput.focus()
    allApps = allApps == null ? (@json($allApps)) : allApps
    allAppsTopDrawer = allAppsTopDrawer == null ? (@json($allAppsTopDrawer)) : allAppsTopDrawer
    url = (@json($route))
    currentUserIsAdmin = (@json($currentUserIsAdmin))
    render(filterAllAppCheckAdmin(allApps),url)
    searchInput.addEventListener("input",(e)=>{
        const value = e.target.value.toLowerCase();
        if(value.length == 0){
            render(filterAllAppCheckAdmin(allApps),url)
        }else{
            apps = allApps.filter(app => {
                if(!currentUserIsAdmin){
                    return app.hidden ? false : matchRegex(value,app)
                }
                return matchRegex(value,app)
            })
            render(apps,url)
        }
    })
</script>
@endsection
  
  
  