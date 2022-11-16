@extends('layouts.app')
@section('title', 'Set Permissions to Roles')
@section('content')
<main class="h-full overflow-y-auto">
    <div class="container mx-auto grid px-6">
        <label for="roles" class="mb-2 block text-sm font-medium text-gray-900 dark:text-gray-400">Select an
            option role</label>
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <form action="{{ route('setpermissions.store') }}" method="POST">
                @csrf
                <select name="role" id="roles" onchange="this.form.submit()" class="role block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                    @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @isset($roleSelected) {{ $roleSelected->name == $role->name ? 'selected' : '' }} @endisset>
                        {{ $role->name }}
                    </option>
                    @endforeach
                </select>
            </form>

        </div>
        <div class="mt-2 mb-8 w-full overflow-hidden rounded-lg border shadow-sm bg-white dark:bg-gray-800 ">
            <div class="w-full overflow-x-auto">
                <form action="{{ route('setpermissions.store2') }}" method="POST">
                    @csrf
                    <table class="whitespace-no-wrap w-full">
                        <thead>
                            <tr class="border-b text-center bg-gray-50 text-xs font-semibold tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                <th class="px-4 py-3 text-center">Model</th>
                                @foreach ($removeLastPermissionNames as $value)
                                <th class="px-4 py-3  text-center">{{ Str::headline($value) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($entities as $entity)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-center text-sm">
                                    <span class="cursor-pointer select-all" data-model="{{ $entity->getTable() }}">{{ $entity->getTable() }}</span>
                                </td>
                                @foreach ($permissions as $permission)
                                @php
                                $var = explode('-', $permission->name);
                                $lastNamePermission = end($var);
                                @endphp
                                @if ($lastNamePermission == $entity->getTable())
                                <td class="text-center" title="{{ $permission->name }}">
                                    <input type="hidden" name="role" value="{{ $selected }}">
                                    <input type="hidden" name="model[]" value="{{ $entity->getTable() }}">
                                    <input type="checkbox" name="checked[]" value="{{ $permission->name }}" title="{{ $permission->name }}" @isset($permissionsRoles) @foreach ($permissionsRoles as $item) @if ($item->name == $permission->name)
                                    @checked(true)
                                    @endif @endforeach
                                    @endisset
                                    class="check-{{ $entity->getTable() }} h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                </td>
                                @endif
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                    <button class="focus:shadow-outline-purple my-2 ml-2 rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-400 focus:outline-none active:bg-emerald-600" type="submit">Save Change</button>
                </form>

            </div>
        </div>
    </div>
</main>
<script>
    $(document).ready(function() {
        $('.select-all').click(function() {
            var model = $(this).attr('data-model')
            var set_checked = !$('.check-' + model)
                .first()
                .is(':checked')
            $('.check-' + model).prop('checked', set_checked)
        })
    })
</script>
@endsection