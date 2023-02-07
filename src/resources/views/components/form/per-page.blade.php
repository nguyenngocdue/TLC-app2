<form action="{{$route}}" method="post" class="w-full lg:w-1/3 p-2 lg:p-0">
    @method('PUT')
    @csrf
    <div class="flex items-center lg:justify-end">
        <div class="mr-1 w-24">
            <input type="hidden" name='_entity' value="{{ $type }}">
            <input type="hidden" name='action' value="updatePerPage">
            <input type="text" name="page_limit" class="text-right block w-24 rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="{{ $pageLimit }}">
        </div>
        <div class="mt-2">/page  </div>
        <div>
            <x-renderer.button htmlType="submit" type="primary"><i class="fas fa-arrow-right"></i></x-renderer.button>
        </div>
    </div>
</form>