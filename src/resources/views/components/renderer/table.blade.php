<div class="my-2 flex flex-col rounded-lg border border-gray-300 dark:border-gray-600">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block w-full sm:px-6 lg:px-8">
            @if ($header)
                <div
                    class='grid1 border-t bg-gray-100 px-4 py-3 text-xs font-semibold tracking-wide text-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sm:grid-cols-9'>
                    {!! $header !!}
                </div>
            @endif
            <div class="table-wrp block max-h-[{{ $maxH }}rem] overflow-x-auto rounded-t-lg">
                <table class='whitespace-no-wrap w-full text-sm' style="table-layout: auto">
                    <colgroup>
                        {!! $colgroup !!}
                    </colgroup>
                    <thead class="sticky top-0 z-10">
                        <tr
                            class='border-b bg-gray-100 text-center text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300'>
                            {!! $columnsRendered !!}
                        </tr>
                    </thead>
                    @isset($headerRendered)
                        <thead class="top-{{ $headerTop }} sticky z-10">
                            <tr
                                class='border-b bg-gray-100 text-center text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300'>
                                {!! $headerRendered !!}
                            </tr>
                        </thead>
                    @endisset
                    <tbody class='divide-y bg-white dark:divide-gray-700 dark:bg-gray-800'>
                        {!! $tr_td !!}
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
            <div
                class='grid border-t rounded-b-lg bg-gray-100 px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sm:grid-cols-9'>
                <span class='col-span-3 flex items-center'>
                    {{ $showing }}
                </span>
                <span class="col-span-2"></span>
                <span class="col-span-4 mt-2 flex sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {!! $pagination !!}
                    </nav>
                </span>
            </div>
            @if ($footer)
                <div
                    class='grid1 border-t bg-gray-100 px-4 py-3 text-xs font-semibold tracking-wide text-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sm:grid-cols-9'>
                    {!! $footer !!}
                </div>
            @endif
        </div>
    </div>
</div>

@if (env('ENV_OF_FORTUNE1'))
    @roleset('admin')
    <div x-show="open" class="grid grid-cols-3 gap-4">
        @dump($columns)
        <div>
            @dump(is_object($dataSource) ? 'DataSource is OBJECT' : 'DataSource is ARRAY')
            @dump(is_object($dataSource) ? $dataSource->items() : $dataSource)
        </div>
    </div>
    <br />
    @endroleset
@endif
