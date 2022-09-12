<!-- Desktop sidebar -->
@php
    $sideBar = json_decode(file_get_contents(storage_path() . "/json/configs/view/dashboard/sidebarProps.json"),true);
@endphp
<aside
class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0"
>
<div class="py-4 text-gray-500 dark:text-gray-400">
  <div class="relative text-center">
    <a
    class="inline-flex text-lg font-bold text-gray-800 dark:text-gray-200"
    href="#"
    >
      <img class="object-cover h-9" src="{{asset('logo/tlc.svg')}}" alt="logo">
    </a>
  </div>
  
  <ul class="mt-6">
    @foreach($sideBar as $group)
        <li class="relative px-6 py-3">{{$group['title']}}</li>
        @foreach ($group['items'] as $key => $item)
            <li class="relative px-6 py-3" x-data="{isActive:false , open:false}">
                <a
                href="#"
                class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                @click="$event.preventDefault(); open = !open"
                role="button"
                aria-haspopup="true"
                  :aria-expanded="(open || isActive) ? 'true' : 'false'"
                >
                <span class="inline-flex items-center">
                    <i class="w-5 h-5 {{$item['icon']}}"></i>
                    <span class="ml-4">{{$item['title']}}</span>
                </span>
                <svg
                    class="w-4 h-4"
                    aria-hidden="true"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                    ></path>
                </svg>
                </a>
                <div  
                  role="menu"
                  x-show="open"
                  class="mt-2 space-y-2 px-7"
                  aria-label="Dashboards">
                    @foreach ($item['items'] as $value)
                      <a role="menuitem" class="block p-2 text-sm text-gray-400 transition-colors duration-200 rounded-md dark:text-gray-400 dark:hover:text-light hover:text-gray-700" href="{{url($value['href'])}}">
                        <span><i class="{{$value['icon']}}"></i>  {{$value['title']}}</span>
                      </a>
                    @endforeach
                </div>
            </li>
        @endforeach
        @if ($group['divider'] === 'true')
          <hr class="my-2"/>
        @else
        @endif
    @endforeach
    
  </ul>
  
</div>
</aside>
<!-- Mobile sidebar -->
<!-- Backdrop -->
<div
x-show="isSideMenuOpen"
x-transition:enter="transition ease-in-out duration-150"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in-out duration-150"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
></div>
<aside
class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white dark:bg-gray-800 md:hidden"
x-show="isSideMenuOpen"
x-transition:enter="transition ease-in-out duration-150"
x-transition:enter-start="opacity-0 transform -translate-x-20"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in-out duration-150"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0 transform -translate-x-20"
@click.away="closeSideMenu"
@keydown.escape="closeSideMenu"
>
<div class="py-4 text-gray-500 dark:text-gray-400">
  <a
    class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200"
    href="#"
  >
    Windmill
  </a>
  <ul class="mt-6">
    @foreach($sideBar as $group)
        <li class="relative px-6 py-3">{{$group['title']}}</li>
        @foreach ($group['items'] as $key => $item)
              <li class="relative px-6 py-3">
                <button
                  class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                  @click="togglePagesMenu"
                  aria-haspopup="true"
                >
                  <span class="inline-flex items-center">
                    <i class="w-5 h-5 {{$item['icon']}}"></i>
                    <span class="ml-4">{{$item['title']}}</span>
                  </span>
                  <svg
                    class="w-4 h-4"
                    aria-hidden="true"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </button>
                <template x-if="isPagesMenuOpen">
                  <ul
                    x-transition:enter="transition-all ease-in-out duration-300"
                    x-transition:enter-start="opacity-25 max-h-0"
                    x-transition:enter-end="opacity-100 max-h-xl"
                    x-transition:leave="transition-all ease-in-out duration-300"
                    x-transition:leave-start="opacity-100 max-h-xl"
                    x-transition:leave-end="opacity-0 max-h-0"
                    class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                    aria-label="submenu"
                  >
                    @foreach ($item['items'] as $value)
                        <li
                        class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                        >
                        <a class="w-full" href="{{url($value['href'])}}">
                            <i class="{{$value['icon']}}"></i>
                            <p>{{$value['title']}}</p>
                        </a>
                        </li>
                    @endforeach
                  </ul>
                </template>
              </li>
            @endforeach
            @if ($group['divider'] === 'true')
              <hr class="my-2"/>
            @else
            @endif
    @endforeach
  </ul>
</div>
</aside>