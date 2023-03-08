<form action="{{$route}}" method="post" class="w-full lg:w-1/3 p-2 lg:p-0">
    @method('PUT')
    @csrf
    <div class="flex items-center lg:justify-end">
        <div class="w-28">
            <input type="hidden" name='_entity' value="{{ $type }}">
            <input type="hidden" name='action' value="updatePerPage">
            {{-- <input type="text" name="page_limit" class="text-right block w-24 rounded-md border border-slate-300 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="{{ $pageLimit }}"> --}}
            <select name="per_page" class="block w-full rounded-md border bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 px-1 py-2 placeholder-slate-400 shadow-sm focus:border-purple-400 dark:focus:border-blue-600 focus:outline-none sm:text-sm">
                @foreach([10,20,30,40,50,100] as $value)
                <option class="text-sm" value="{{$value}}" @selected($perPage==$value)>{{$value}} / page</option>
                @endforeach
            </select>
        </div>
        {{-- <div class="mt-2 dark:text-white">/page  </div> --}}
        <div>
            <x-renderer.button htmlType="submit" type="primary"><i class="fas fa-arrow-right"></i></x-renderer.button>
        </div>
    </div>
</form>
