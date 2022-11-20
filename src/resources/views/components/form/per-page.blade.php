<form action="{{$action}}" method="post">
    @method('PUT')
    @csrf
    <div class="mt-2 flex">
        <div class="mr-1 w-24">
            <input type="hidden" name='_entity_page' value="{{ $type }}">
            <input type="text" name="page_limit" class="text-right block w-24 rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="{{ $pageLimit }}">
        </div>
        <div>
            <x-renderer.button htmlType="submit" type="primary"><i class="fas fa-arrow-right"></i></x-renderer.button>
        </div>
    </div>
</form>