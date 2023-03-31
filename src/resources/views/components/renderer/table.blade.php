@if($noCss)
@php
    $columnsRendered = preg_replace('/<th class=\'.*?\'/', "<th class='text-center bg-gray-50 border border-gray-400  py-2'", $columnsRendered);
    $tr_td = preg_replace('/<tr class=\'.*?\'/', "<tr class='border border-gray-400'", $tr_td);
@endphp
<table class="w-full min-w-full max-w-full">
    <thead>{!! $columnsRendered !!}</thead>
    <tbody>{!! $tr_td !!}</tbody>
</table>
@else
<div class="border rounded-lg border-gray-300 dark:border-gray-600">
    <div>
        <div class="inline-block w-full sm:px-0 lg:px-0">
            @if ($header)
            <div class='grid1 border-t bg-gray-100 px-4 py-3 text-xs font-semibold1 tracking-wide text-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sm:grid-cols-9'>
                {!! $header !!}
            </div>
            @endif
            @if($showPaginationTop)
            <div class='w-full grid grid-cols-12 border-b border-red-50 rounded-t-lg bg-gray-100 px-4 py-3 text-xs font-semibold1 tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300'>
                <span class='lg:col-span-3 md:col-span-12 flex gap-1'>{!! Blade::render( $topLeftControl) !!}</span>
                <span class="lg:col-span-3 md:col-span-12 flex gap-1 justify-center">{!! Blade::render($topCenterControl) !!}</span>
                <span class="lg:col-span-6 md:col-span-12 flex gap-1 justify-end">
                    {!! $showing !!}
                    {!! $pagination !!}
                    {!! Blade::render($topRightControl) !!}
                </span>
            </div>
            @endif
            <div class="table-wrp block {{ $maxH }} overflow-x-auto {{$showPaginationTop ? "border-t":"rounded-t-lg"}}">
                <table id="{{$tableName}}" class='whitespace-no-wrap w-full text-sm' style="table-layout: auto">
                    <colgroup>
                        {!! $colgroup !!}
                    </colgroup>
                    <thead class="sticky z-10 top-0">
                        <tr class="{{$trClassList}}">
                            {!! $columnsRendered !!}
                        </tr>
                    </thead>
                    @isset($headerRendered)
                    <thead class="sticky z-10 top-{{ $headerTop }}">
                        <tr class="{{$trClassList}}">
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
            @if($showPaginationBottom)
            <div class='w-full grid grid-cols-12 border-t border-red-50 rounded-b-lg bg-gray-100 px-4 py-3 text-xs font-semibold1 tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300'>
                <span class='lg:col-span-3 md:col-span-12 flex gap-1'>{!! Blade::render($bottomLeftControl) !!}</span>
                <span class="lg:col-span-3 md:col-span-12 flex gap-1 justify-center">{!! Blade::render($bottomCenterControl) !!}</span>
                <div class="lg:col-span-6 md:col-span-12 flex gap-1 justify-end">
                    {!! $showing !!}
                    {!! $pagination !!}
                    {!! Blade::render($bottomRightControl) !!}
                </div>
            </div>
            @endif
            @if ($footer)
            <div class='grid1 border-t bg-gray-100 px-4 py-3 text-xs font-semibold1 tracking-wide text-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sm:grid-cols-9'>
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
@endif

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
