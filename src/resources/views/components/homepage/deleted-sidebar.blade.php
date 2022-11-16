<!-- Desktop sidebar -->

@php
try {
$sideBar = json_decode(file_get_contents(storage_path() . '/json/configs/view/dashboard/sidebarProps.json'), true);
} catch (\Throwable $th) {
$error = '<span class="ml-5 flex text-sm text-red-500">Setting Side Bar Prop json fail! Please fix file sidebarProp.json before
    Run. </span>';
}
@endphp
@isset($error)
{!! $error !!}
@else
<aside class="dark:border-primary-darker dark:bg-darker z-20 hidden w-72 flex-shrink-0 border-r bg-white md:block">
    <div class="py-4 text-gray-500 dark:text-gray-400">
        <div class="relative text-center">
            <a class="inline-flex text-lg font-bold text-gray-800 dark:text-gray-200" href="/">
                <img class="h-9 object-cover" src="{{ asset('logo/tlc.svg') }}" alt="logo">
            </a>
        </div>
        <nav aria-label="Main" class="flex-1 space-y-2 overflow-y-hidden px-2 py-4 hover:overflow-y-auto">

            @foreach ($sideBar as $group)
            @if ($group['title'] === 'Admin')
            @roleset('admin|super-admin') <div class="relative px-6 py-3">{{ $group['title'] }}
            </div>
            @foreach ($group['items'] as $key => $item)
            <div class="relative px-6 py-3" x-data="{ isActive: false, open: {{ Request::is($item['href_parent'] . '/*') ? 'true' : 'false' }} }">
                <a href="#" class="{{ Request::is($item['href_parent'] . '/*') ? 'text-blue-500 hover:text-blue-800' : 'hover:text-gray-800 dark:hover:text-gray-200' }} inline-flex w-full items-center justify-between text-sm font-semibold transition-colors duration-150" @click="$event.preventDefault(); open = !open" role="button" aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'">
                    <span class="inline-flex items-center">
                        <i class="{{ $item['icon'] }} h-5 w-5"></i>
                        <span class="ml-4">{{ $item['title'] }}</span>
                    </span>
                    <svg class="h-4 w-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </a>
                <div role="menu" x-show="open" class="mt-2 space-y-2" aria-label="Dashboards">
                    @foreach ($item['items'] as $value)
                    <a role="menuitem" class="dark:hover:text-light {{ Request::is($value['href']) ? 'text-blue-500 hover:text-blue-700' : 'text-gray-400 hover:text-gray-700 dark:text-gray-400' }} block rounded-md p-2 text-sm transition-colors duration-200" href="{{ url($value['href']) }}">
                        <span><i class="{{ $value['icon'] }}"></i> {{ $value['title'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
            @endroleset
            @else
            <div class="relative px-6 py-3">{{ $group['title'] }}</div>
            @php
            sort($group['items']);
            @endphp
            @foreach ($group['items'] as $key => $item)
            <div class="relative px-6 py-3" x-data="{ isActive: false, open: {{ Request::is($item['href_parent'] . '/*') ? 'true' : 'false' }} }">
                <a href="#" class="{{ Request::is($item['href_parent'] . '/*') ? 'text-blue-500 hover:text-blue-800' : 'hover:text-gray-800 dark:hover:text-gray-200' }} inline-flex w-full items-center justify-between text-sm font-semibold transition-colors duration-150" @click="$event.preventDefault(); open = !open" role="button" aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'">
                    <span class="inline-flex items-center">
                        <i class="{{ $item['icon'] }} h-5 w-5"></i>
                        <span class="ml-4">{{ Str::title( Str::replace("_"," ",$item['title'])) }}</span>
                    </span>
                    <svg class="h-4 w-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </a>
                <div role="menu" x-show="open" class="mt-2 space-y-2" aria-label="Dashboards">
                    @foreach ($item['items'] as $value)
                    <a role="menuitem" class="{{ Request::is($value['href']) ? 'text-blue-500 hover:text-blue-700' : 'text-gray-400 hover:text-gray-700 dark:text-gray-400' }} dark:hover:text-light block rounded-md p-2 text-sm transition-colors duration-200" href="{{ url($value['href']) }}">
                        <span><i class="{{ $value['icon'] }}"></i> {{ Str::title( Str::replace("_"," ",$value['title'])) }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
            @endif

            @if ($group['divider'] === 'true')
            <hr class="my-2" />
            @else
            @endif
            @endforeach
        </nav>
    </div>
</aside>
<!-- Mobile sidebar -->
<!-- Backdrop -->
<div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>
<aside class="fixed inset-y-0 z-20 mt-16 w-72 flex-shrink-0 overflow-y-auto bg-white dark:bg-gray-800 md:hidden" x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150" x-transition:enter-start="opacity-0 transform -translate-x-20" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 transform -translate-x-20" @click.away="closeSideMenu" @keydown.escape="closeSideMenu">
    <div class="py-4 text-gray-500 dark:text-gray-400">
        <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="#">
            Windmill
        </a>
        <ul class="mt-6">
            @foreach ($sideBar as $group)
            <li class="relative px-6 py-3">{{ $group['title'] }}</li>
            @foreach ($group['items'] as $key => $item)
            <li class="relative px-6 py-3">
                <button class="inline-flex w-full items-center justify-between text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200" @click="togglePagesMenu" aria-haspopup="true">
                    <span class="inline-flex items-center">
                        <i class="{{ $item['icon'] }} h-5 w-5"></i>
                        <span class="ml-4">{{ $item['title'] }}</span>
                    </span>
                    <svg class="h-4 w-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <template x-if="isPagesMenuOpen">
                    <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="mt-2 space-y-2 overflow-hidden rounded-md bg-gray-50 p-2 text-sm font-medium text-gray-500 shadow-inner dark:bg-gray-900 dark:text-gray-400" aria-label="submenu">
                        @foreach ($item['items'] as $value)
                        <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                            <a class="w-full" href="{{ url($value['href']) }}">
                                <i class="{{ $value['icon'] }}"></i>
                                <p>{{ $value['title'] }}</p>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </template>
            </li>
            @endforeach
            @if ($group['divider'] === 'true')
            <hr class="my-2" />
            @else
            @endif
            @endforeach
        </ul>
    </div>
</aside>
@endisset
