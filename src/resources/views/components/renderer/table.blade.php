<div class='w-full overflow-hidden rounded-lg shadow-xs'>
    <div class='w-full overflow-x-auto'>
        <table class='w-full whitespace-no-wrap'>
            <thead>
                <tr class='text-xs font-semibold tracking-wide text-center text-gray-500 border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800'>
                    {!! $columnsRendered !!}
                </tr>
            </thead>
            <tbody class='bg-white divide-y dark:divide-gray-700 dark:bg-gray-800'>
                {!! $trtd !!}
            </tbody>
        </table>
    </div>
    <div class='grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800'>
        <span class='flex items-center col-span-3'>
            {{ $showing }}
        </span>
        <span class="col-span-2"></span>
        <span class="col-span-4 mt-2 flex sm:mt-auto sm:justify-end">
            <nav aria-label="Table navigation">
                {!! $pagination !!}
            </nav>
        </span>
    </div>
</div>