@extends('layouts.app')

@section('topTitle', 'Permission Library')
@section('title', Str::headline($type))

@section('content')
    <main class="h-full">
        <div class="container mx-auto grid px-6">
            <form action="{{ route($type . '.index') }}" method="GET" class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <div>
                    <input type="text" name="search"
                        class="block w-full rounded-md border border-slate-300 bg-white dark:bg-gray-800 dark:border-gray-600 px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm"
                        value="{{ $search }}">
                </div>
                <div>
                    <button type="submit"
                        class="focus:shadow-outline-purple rounded-lg border border-transparent  bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-emerald-200 focus:outline-none active:bg-emerald-600">Search</button>
                </div>
            </form>
            <div class="mt-2 grid grid-cols-2 gap-5">
                <form action="{{ route($type . '.store') }}" method="POST" class="mt-2 grid grid-cols-2 gap-5">
                    @csrf
                    <div>
                        <input type="text" name="name"
                            class="block w-full rounded-md border border-slate-300 bg-white dark:bg-gray-800 dark:border-gray-600 px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm"
                            value="">
                        @error('name')
                            <span class="ml-2 text-xs font-light text-red-600" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div>
                        <button type="submit"
                            class="focus:shadow-outline-purple rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-emerald-200 focus:outline-none active:bg-emerald-600">Add
                            {{ Str::of(Str::headline($type))->singular() }}</button>
                    </div>
                </form>
                <form action="{{ route($type . '.index') }}" method="GET">
                    <div class="mt-2 flex">
                        <div class="mr-1 w-12">
                            <input type="text" name="page_limit"
                                class="block w-12 rounded-md border border-slate-300 bg-white dark:bg-gray-800 dark:border-gray-600 px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm"
                                value="{{ $pageLimit }}">
                        </div>
                        <div>
                            <button type="submit"
                                class="focus:shadow-outline-purple rounded-lg border border-transparent bg-emerald-500 px-2 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-emerald-200 focus:outline-none active:bg-emerald-600"><i
                                    class="fas fa-arrow-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="mt-2 mb-8 w-full overflow-hidden rounded-lg border bg-white shadow-sm dark:bg-gray-800 dark:border-gray-600">
                <div class="w-full overflow-x-auto">
                    <table class="whitespace-no-wrap w-full">
                        <thead>
                            <tr
                                class="border-b bg-gray-50 text-center text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Gruard Name</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($data as $value)
                                <tr class="text-gray-700 dark:text-gray-300">
                                    <td class="px-4 py-3 text-sm">
                                        {{ $value->id }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $value->name }}
                                    </td>
                                    <td class="px-4 py-3 text-sm" s>
                                        {{ $value->guard_name }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex">
                                            <button data-url="{{ route($type . '.edit', $value->id) }}"
                                                class="btn-edit focus:shadow-outline-gray rounded-lg px-2 py-2 text-sm font-medium leading-5 text-red-600 focus:outline-none dark:text-red-400"
                                                type="button" onclick="toggleModal('modal-id')">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button
                                                class="focus:shadow-outline-gray btn-delete-render rounded-lg px-2 py-2 text-sm font-medium leading-5 text-red-600 focus:outline-none dark:text-red-400"
                                                data-url="{{ route($type . '.destroy', $value->id) }}" ​ type="button"><i
                                                    class="fas fa-trash"></i></button>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
                <div
                    class="grid border-t bg-gray-50 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sm:grid-cols-9">
                    <span class="col-span-3 flex items-center">
                        @if (isset($data) && count($data) > 0)
                            {{ $data->links('dashboards.pagination.showing') }}
                        @endif
                    </span>
                    <span class="col-span-2"></span>
                    <span class="col-span-4 mt-2 flex sm:mt-auto sm:justify-end">
                        <nav aria-label="Table navigation">
                            @if (isset($data) && count($data) > 0)
                                {{ $data->links('dashboards.pagination.pagination') }}
                            @endif
                        </nav>
                    </span>
                </div>
            </div>
            @include('admin.render.edit')
        </div>
    </main>
    <script src="{{ asset('js/edit-delete-permission.js') }}"></script>
    {{-- render.blade --}}
@endsection
