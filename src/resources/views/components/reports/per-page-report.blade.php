<form action="{{route($routeName)}}" method="GET" class="w-full lg:w-1/3 p-2 lg:p-0">
    @csrf
    <div class="flex items-center lg:justify-end">
        <div class="w-28 px-2">
            <input type="hidden" name='_entity' value="{{ $entity }}">
            <input type="hidden" name='action' value="updatePerPage{{$typeReport}}">
            <input type="hidden" name='type_report' value="{{$typeReport}}">
            <select name="page_limit" class="block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm">
                @foreach([10,20,30,40,50,100] as $perPage)
                <option class="text-sm" value="{{$perPage}}" @selected($pageLimit==$perPage)>{{$perPage}} / page</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-renderer.button htmlType="submit" type="primary"><i class="fas fa-arrow-right"></i></x-renderer.button>
        </div>
    </div>
</form>
