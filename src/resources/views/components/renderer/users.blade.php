@if (count($users) <= 0) <span class='w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-blue-400 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500'>
    Please check relationship</span>
    @else
    <div class="flex items-center text-sm">
        @if (count($users) > 3)

        @php
        $items = $users->all();
        $itemShows = array_slice($items, 0, 3);
        $countRemaining = count($items) - count($itemShows);
        @endphp
        @foreach ($itemShows as $item)
        <!-- Avatar with inset shadow -->
        <div class="relative hidden h-8 w-8 rounded-full md:block" title="{{ $item->name }}">
            <img class="h-full w-full rounded-full object-cover" src="https://wp.tlcmodular.com/wp-content/uploads/2022/07/bfdc18a057769428cd67-150x150.jpg" alt="img" loading="lazy" />
            <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
        </div>
        @endforeach
        <div class="relative hidden h-8 w-8 rounded-full text-center md:block">
            <div class="absolute inset-0 items-center rounded-full bg-gray-300 shadow-inner">
                <span class="items-center text-center text-black">+{{ $countRemaining }}</span>
            </div>
        </div>
        @else
        @foreach ($users as $user)
        <!-- Avatar with inset shadow -->
        <div class="relative hidden h-8 w-8 rounded-full md:block" title="{{ $user->name }}">
            <img class="h-full w-full rounded-full object-cover" src="https://wp.tlcmodular.com/wp-content/uploads/2022/07/bfdc18a057769428cd67-150x150.jpg" alt="img" loading="lazy" />
            <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
        </div>
        @endforeach

        @endif

    </div>
    @endif