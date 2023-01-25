<div class="mt-6"></div>
<ul>
    @foreach($items as $item)
    @if($item === '-')
    <hr />
    @else
    <li class="relative px-6 py-3" x-data="{opening: 0, active: {{$item['isActive'] ? 1 : 0}},}">
        @if($item['isActive'])
        <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
        @endif

        @if(isset($item['children']))
        <button @class([ "text-gray-800 dark:text-gray-100"=> $item['isActive'],
            "inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"])
            @click="opening = ! opening" aria-haspopup="true">
            <span class="inline-flex items-center">
                <i class="{{$item['icon']}}"></i>
                <span class="ml-4 text-left">{{$item['title']}}</span>
            </span>
            <i class="fa-duotone fa-chevron-down"></i>
        </button>
        <template x-if="opening || active">
            <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                @foreach($item['children'] as $child)
                <li class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                    @if($child['title'] === '-')
                    <hr />
                    @else
                    <a class="px-2 w-full" href={{$child['href']}}>{{$child['title']}}</a>
                    @endif
                </li>
                @endforeach
            </ul>
        </template>
        @else
        <a href="index.html" @class([ "text-gray-800 dark:text-gray-100"=> $item['isActive'],
            "inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200",])>
            {!!$item['icon']!!}
            <span class="ml-4 text-left">{{$item['title']}}</span>
        </a>
        @endif
    </li>
    @endif
    @endforeach
</ul>
<br />
<br />
<br />
<br />
<br />