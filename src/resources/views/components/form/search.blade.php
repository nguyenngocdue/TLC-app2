<form action="{{$action}}" method="GET">
    <div class="mt-2 grid grid-cols-2 gap-5">
        <div>
            <input type="text" name="search" class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="{{ request('search') }}">
        </div>
        <div>
            <x-renderer.button htmlType="submit" type="primary">Search</x-renderer.button>
        </div>
    </div>
</form>