@php
    $formName = "updatePerPage";
@endphp
<form class="">
  <button type="submit"  type="hidden" class="hidden">Submit</button>
</form>
<form name="{{$formName}}" method="GET" class="m-0">
        <div class="flex items-center lg:justify-end no-print">
            <div class="w-28">
                <input type="hidden" name="action" value="updateReport2">
                <input type="hidden" name="entity_type" value="{{$entityType}}">
                <input type="hidden" name="entity_type2" value="{{$reportType2}}">
                <input type="hidden" name="report_id" value="{{$reportId}}">
                <select onchange="document.forms['{{$formName}}'].submit()" name="per_page" class="block w-full text-gray-700 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 sm:text-sm focus:border-1 focus:border-blue-600 dark:focus:border-blue-600 focus:outline-none px-2 py-2">
                    @foreach([10, 20, 30, 40, 50, 100] as $perPage)
                    <option class="text-sm" value="{{$perPage}}" @selected($pageLimit == $perPage)>{{$perPage}} / page</option>
                    @endforeach
                </select>
            </div>
        </div>
</form>