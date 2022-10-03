@extends('layouts.app')
@section('content')
    <main class="h-full overflow-y-auto">
        <div class="container mx-auto grid px-0">
            <div
                class="focus:shadow-outline-purple my-4 flex items-center justify-between rounded-lg bg-purple-600 p-3 text-base font-semibold text-purple-100 shadow-md focus:outline-none">
                <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    <span>Set Permissions -> Role</span>
                </div>
                <span>View more →</span>
            </div>
            <label for="roles" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-400">Select an
                option role</label>
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <form action="{{ route('setpermissions.store') }}" method="POST">
                    @csrf
                    <select name="role" id="roles" onchange="this.form.submit()"
                        class="role block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                @isset($roleSelected)
                                {{ $roleSelected->name == $role->name ? 'selected' : '' }}
                                @endisset>
                                {{ $role->name }} </option>
                        @endforeach
                    </select>
                </form>

            </div>
            <div class="mt-2 mb-8 w-full overflow-hidden rounded-lg border shadow-sm">
                <div class="w-full overflow-x-auto">
                    <form action="{{ route('setpermissions.store2') }}" method="POST">
                        @csrf
                        <table class="whitespace-no-wrap w-full">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3 text-center">Read</th>
                                    <th class="px-4 py-3 text-center">Edit</th>
                                    <th class="px-4 py-3 text-center">Create</th>
                                    <th class="px-4 py-3 text-center">Edit Other</th>
                                    <th class="px-4 py-3 text-center">Delete</th>
                                    <th class="px-4 py-3 text-center">Delete Other</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                                @foreach ($entities as $entity)
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3 text-sm">
                                            {{ $entity->getTable() }}
                                        </td>
                                        @foreach ($permissions as $permission)
                                            @if (str_contains($permission->name, Str::singular($entity->getTable())))
                                                <td class="text-center" title="{{ $permission->name }}">
                                                    <input type="hidden" name="role" value="{{ $selected }}">
                                                    <input type="hidden" name="model[]" value="{{ $entity->getTable() }}">
                                                    <input type="checkbox" name="checked[]" value="{{ $permission->name }}"
                                                        title="{{ $permission->name }}"
                                                        @isset($permissionsRoles)
                                                            @foreach ($permissionsRoles as $item)
                                                            @if ($item->name == $permission->name)
                                                                @checked(true)
                                                            @endif @endforeach
                                                        @endisset
                                                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                                </td>
                                            @elseif (str_contains($permission->name, $entity->getTable()))
                                                <td class="text-center" title="{{ $permission->name }}">
                                                    <input type="hidden" name="role" value="{{ $selected }}">
                                                    <input type="hidden" name="model[]" value="{{ $entity->getTable() }}">
                                                    <input type="checkbox" name="checked[]" value="{{ $permission->name }}"
                                                        title="{{ $permission->name }}"
                                                        @isset($permissionsRoles)
                                                        @foreach ($permissionsRoles as $item)
                                                        @if ($item->name == $permission->name)
                                                            @checked(true)
                                                        @endif @endforeach
                                                    @endisset
                                                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                        <button
                            class="focus:shadow-outline-purple my-2 ml-2 rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-emerald-200 focus:outline-none active:bg-emerald-600"
                            type="submit">Save Change</button>
                    </form>

                </div>
                {{-- <div
                    class="grid border-t bg-gray-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:grid-cols-9">
                    <span class="col-span-3 flex items-center">
                        @if (isset($data) && count($data) > 0)
                            {{ $data->links('dashboards.pagination.showing') }}
                        @endif
                    </span>
                    <span class="col-span-2"></span>
                    <span class="col-span-4 mt-2 flex sm:mt-auto sm:justify-end">
                        <nav aria-label="Table navigation">
                            @if (isset($data) && count($data) > 0)
                                {{ $data->links('dashboards.pagination.template') }}
                            @endif
                        </nav>
                    </span>
                </div> --}}
            </div>
            {{-- @include('admin.render.edit') --}}
        </div>
    </main>
@endsection
