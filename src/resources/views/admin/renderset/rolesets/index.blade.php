@extends('layouts.app')
@section('title', 'Set Rolesets to Users')
@section('content')
<main class="h-full">
    <div class="container mx-auto grid px-6">
        <div class="mt-2 grid grid-cols-2 gap-5">
            <form action="{{ route('setrolesets.index') }}" method="GET">
                <div class="mt-2 grid grid-cols-2 gap-5">
                    <div>
                        <input type="text" name="search" class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="{{ $search }}">
                    </div>
                    <div>
                        <button type="submit" class="focus:shadow-outline-purple rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-emerald-200 focus:outline-none active:bg-emerald-600">Search</button>
                    </div>
                </div>
            </form>
            <form action="{{ route('setrolesets.index') }}" method="GET">
                <div class="mt-2 flex">
                    <div class="mr-1 w-12">
                        <input type="text" name="page_limit" class="block w-12 rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="{{ $pageLimit }}">
                    </div>
                    <div>
                        <button type="submit" class="focus:shadow-outline-purple rounded-lg border border-transparent bg-emerald-500 px-2 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-emerald-200 focus:outline-none active:bg-emerald-600"><i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="mt-2 mb-8 w-full overflow-hidden rounded-lg border shadow-sm bg-white dark:bg-gray-800 ">
            <div class="w-full overflow-x-auto">
                <table class="whitespace-no-wrap w-full">
                    <thead>
                        <tr class="border-b text-center bg-gray-50 text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Role Set</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($users as $user)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-center text-sm">
                                {{ $user->id }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm">
                                {{ $user->name }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm">
                                @foreach ($user->roleSets as $item)
                                <span class="rounded-full bg-green-100 px-2 py-1 font-semibold leading-tight text-green-700 dark:bg-green-700 dark:text-green-100">
                                    {{ $item->name }}
                                </span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-center text-xs">
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach ($user->getRolesViaRoleSets() as $item)
                                    <span class="block w-full rounded-full bg-green-100 px-2 py-1 font-semibold leading-tight text-green-700 dark:bg-green-700 dark:text-green-100">
                                        {{ $item->name }}
                                    </span>
                                    @endforeach
                                </div>

                            </td>
                            <td class="px-4 py-3 text-center text-sm">
                                <div class="flex">
                                    <a href="{{ route('setrolesets.edit', $user->id) }}" class="focus:shadow-outline-purple rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-emerald-200 focus:outline-none active:bg-emerald-600" type="button">
                                        Role Set
                                    </a>
                                    <a href="{{ route('setrolesets.impersonate', $user->id) }}" class="focus:shadow-outline-purple ml-2 rounded-lg border border-transparent bg-sky-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-sky-200 focus:outline-none active:bg-sky-600" ​type="button">Impersonate</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
            <div class="grid border-t bg-gray-50 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:grid-cols-9">
                <span class="col-span-3 flex items-center">
                    @if (isset($users) && count($users) > 0)
                    {{ $users->links('dashboards.pagination.showing') }}
                    @endif
                </span>
                <span class="col-span-2"></span>
                <span class="col-span-4 mt-2 flex sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        @if (isset($users) && count($users) > 0)
                        {{ $users->links('dashboards.pagination.pagination') }}
                        @endif
                    </nav>
                </span>
            </div>
        </div>
        @include('admin.render.edit')
    </div>
</main>
@endsection