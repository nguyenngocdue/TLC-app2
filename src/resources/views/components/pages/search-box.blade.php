<form action="{{ route($type . '_viewall.index') }}" method="GET">
    <div class="mt-2 grid grid-cols-2 gap-5">
        <div>
            <input type="text" name="search" class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="{{ $search }}">
        </div>
        <div>
            <button type="submit" class="focus:shadow-outline-purple rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-emerald-200 focus:outline-none active:bg-emerald-600">Search</button>
        </div>
    </div>
</form>