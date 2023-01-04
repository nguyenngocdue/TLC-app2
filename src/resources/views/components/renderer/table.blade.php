<div class="my-2 flex flex-col rounded-lg border border-gray-300">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 inline-block w-full sm:px-6 lg:px-8">
        @if($header)
            <div class='grid1 px-4 py-3 text-xs font-semibold tracking-wide text-blue-500 border-t dark:border-gray-700 bg-gray-100 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800'>
                {!! $header !!}
            </div>
        @endif    
        <div class="table-wrp overflow-x-auto block max-h-[40rem]">
            <table class='w-full whitespace-no-wrap text-sm ' style="table-layout: auto">
                <colgroup>
                    {!! $colgroup  !!}                        
                </colgroup>
                <thead class="sticky top-0 z-10">
                    <tr class='text-xs font-semibold tracking-wide text-center text-gray-500 border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800'>
                        {!! $columnsRendered !!}
                    </tr>
                </thead>
                <tbody class='bg-white divide-y dark:divide-gray-700 dark:bg-gray-800'>
                    {!! $trtd !!}
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
        <div class='grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 border-t dark:border-gray-700 bg-gray-100 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800'>
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
        @if($footer)
        <div class='grid1 px-4 py-3 text-xs font-semibold tracking-wide text-blue-500 border-t dark:border-gray-700 bg-gray-100 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800'>
            {!! $footer !!}
        </div>
        @endif
      </div>
    </div>
</div>

@if(env("ENV_OF_FORTUNE1"))
@roleset('admin')
<div x-show="open" class="grid grid-cols-3 gap-4  ">
    @dump($columns)
    <div>
        @dump(is_object($dataSource) ? "DataSource is OBJECT" : "DataSource is ARRAY")
        @dump(is_object($dataSource) ? $dataSource->items(): $dataSource)
    </div>
</div>
<br />
@endroleset
@endif
