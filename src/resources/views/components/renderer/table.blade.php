<div class="rounded-lg border border-gray-300 dark:border-gray-600">
    <div>
        <div class="inline-block w-full sm:px-0 lg:px-0">
            @if ($header)
            <div class='grid1 border-t bg-gray-100 px-4 py-3 text-xs font-semibold tracking-wide text-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sm:grid-cols-9'>
                {!! $header !!}
            </div>
            @endif
            <div class="table-wrp block {{ $maxH }} overflow-x-auto rounded-t-lg">
                <table id="{{$tableName}}" class='whitespace-no-wrap w-full text-sm' style="table-layout: auto">
                    <colgroup>
                        {!! $colgroup !!}
                    </colgroup>
                    <thead class="sticky top-0 z-10">
                        <tr class='border-b bg-gray-100 text-center text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300'>
                            {!! $columnsRendered !!}
                        </tr>
                    </thead>
                    @isset($headerRendered)
                    <thead class="top-{{ $headerTop }} sticky z-10">
                        <tr class='border-b bg-gray-100 text-center text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300'>
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
            <div class='w-full grid border-t rounded-b-lg bg-gray-100 px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sm:grid-cols-9'>
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
            <div class='grid1 border-t bg-gray-100 px-4 py-3 text-xs font-semibold tracking-wide text-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sm:grid-cols-9'>
                {!! $footer !!}
            </div>
            @endif
        </div>
    </div>
</div>
<div class="p-1"></div>
<script>
    table = document.querySelector('#{{$tableName}} tbody')

    nbRows = table.rows.length
    nbCells = table.rows[0].cells.length
    movKey = {
        ArrowUp: (p) => p.r = (p.r !== 2) ? (--p.r - 2) : (nbRows - 1)
        , ArrowDown: (p) => p.r = (p.r !== (nbRows + 1)) ? (++p.r - 2) : (0)
    , }
    array = [...table.querySelectorAll('select')
        , ...table.querySelectorAll('input')
        , ...table.querySelectorAll('textarea')
        , ...table.querySelectorAll('check')
    , ]
    array.forEach((elm) => {
        elm.onfocus = (e) => {
            let sPos = table.querySelector('.select')
                , tdPos = elm.parentNode
            if (sPos) sPos.classList.remove('select')
            tdPos.classList.add('select')
        }
    })
    document.onkeydown = (e) => {
        let sPos = table.querySelector('.select')
        evt = e == null ? event : e
        o = e.srcElement || e.target
        if (!o) {
            return
        }
        if (
            o.tagName !== 'TEXTAREA' &&
            o.tagName !== 'INPUT' &&
            o.tagName !== 'SELECT'
        ) {
            return
        }
        pos = {
            r: sPos ? sPos.parentNode.rowIndex : -1
            , c: sPos ? sPos.cellIndex : -1
        , }
        if (
            sPos &&
            evt.ctrlKey &&
            movKey[evt.code]
        ) {
            let loop = true
                , nxFocus = null
                , cell = null
            do {
                movKey[evt.code](pos)
                cell = table.rows[pos.r].cells[pos.c]
                nxFocus = cell.querySelector('input') ||
                    cell.querySelector('select') ||
                    cell.querySelector('textarea') // get focussable element of <td>
                if (
                    nxFocus &&
                    cell.style.display !== 'none' &&
                    cell.parentNode.style.display !== 'none'
                ) {
                    nxFocus.focus()
                    loop = false
                }
            } while (loop)
        }
    }

</script>

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
