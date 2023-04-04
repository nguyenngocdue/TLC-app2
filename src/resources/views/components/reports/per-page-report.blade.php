{{-- @dump($formName) --}}
<form name="{{$formName}}" method="GET" class="">
    <div class="flex items-center lg:justify-end no-print">
        <div class="w-28 px-2">
            <input type="hidden" name='_entity' value="{{ $entity }}">
            <input type="hidden" name='action' value="updatePerPage{{Str::ucfirst($typeReport)}}">
            <input type="hidden" name='type_report' value="{{$typeReport}}">
            <input type="hidden" name='form_type' value="updatePerPageReport">
            <select onchange="{{$formName}}.submit()" name="per_page" class="block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm">
                @foreach([10,20,30,40,50,100] as $perPage)
                <option class="text-sm" value="{{$perPage}}" @selected($pageLimit==$perPage)>{{$perPage}} / page</option>
                @endforeach
            </select>
        </div>
    </div>
</form>
