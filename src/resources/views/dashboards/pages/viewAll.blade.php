@extends('layouts.app')
@section('content')
<main class="h-full overflow-y-auto">
    <div class="container mx-auto grid px-6">
        <div class="focus:shadow-outline-purple my-4 flex items-center justify-between rounded-lg bg-purple-600 p-3 text-base font-semibold text-purple-100 shadow-md focus:outline-none">
            <div class="flex items-center">
                <span>{{ ucfirst($type) }}</span>
            </div>
            <span>View more →</span>
        </div>
        <x-controls.breadcrumb type={{$type}} />
        @isset($messages)
        <div class="m-auto mt-[15%] mb-4 rounded-lg border border-yellow-300 bg-yellow-50 p-4 dark:bg-yellow-200">
            @foreach ($messages as $message)
            <p><a class="text-blue-500" href="{{ route($message['href']) }}">{{ $message['title'] }}</a> is missing.
            </p>
            @endforeach
        </div>
        @endisset
        @empty($messages)
        <div class="mt-2 grid grid-cols-2 gap-5">
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
            <form action="{{ route($type . '_viewall.update', Auth::id()) }}" method="post">
                @method('PUT')
                @csrf
                <div class="mt-2 flex">
                    <div class="mr-1 w-12">
                        <input type="hidden" name='_entity_page' value="{{ $type }}">
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
                            <th class="px-4 py-3 text-center">Action</th>
                            @isset($data)
                            @foreach ($data as $key => $value)
                            <th class="{{ $key . '_th' }} px-4 py-3 text-center" title="{{ $value['column_name'] }}">
                                {{ $value['label'] }}
                            </th>
                            @endforeach
                            @endisset

                            @isset($data2)
                            @foreach ($data2 as $key => $value)
                            @if ($value['hidden'] === null)
                            <th class="{{ $key . '_th' }} px-4 py-3 text-center" title="{{ $value['relationship'] }}">
                                {{ $value['label'] }}
                            </th>
                            @endif
                            @endforeach
                            @endisset
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                        @isset($data)
                        @if (isset($users) && !count($users) > 0)
                        <x-feedback.alert title="Infomation Data" message="Data empty" />
                        @else
                        @foreach ($users as $key => $user)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                <button class="focus:shadow-outline-gray btn-delete-user rounded-lg px-2 py-2 text-sm font-medium leading-5 text-red-600 focus:outline-none dark:text-red-400" data-url="{{ route($type . '_viewall.destroy', $user->id) }}" ​ type="button"><i class="fas fa-trash"></i></button>
                            </td>
                            @foreach ($data as $key1 => $value)
                            @if ($value['column_name'] === 'id')
                            @if ($value['control'] === 'id')
                            @php
                            $numberRender = str_pad($user[$value['column_name']], 6, '0', STR_PAD_LEFT);
                            $result = '#' . substr($numberRender, 0, 3) . '.' . substr($numberRender, 3, 6);
                            @endphp
                            <td class="{{ $key1 . '_td' }} px-4 py-3 text-sm">
                                <a href="{{ route($type . '_edit.edit', $user[$value['column_name']]) }}" class="text-sm font-normal text-blue-500">{{ $result }}</a>
                            </td>
                            @else
                            <td class="{{ $key1 . '_td' }} px-4 py-3 text-sm">

                                <a href="{{ route($type . '_edit.edit', $user[$value['column_name']]) }}">{{ $user[$value['column_name']] }}</a>
                            </td>
                            @endif
                            @else
                            <td class="{{ $key1 . '_td' }} px-4 py-3 text-sm">
                                @if (!is_array($user[$value['column_name']]))
                                @if ($value['render'] === 'relationship')
                                @php
                                $item = $value['render_detail'];
                                @endphp
                                @switch($item['renderer'])
                                @case('')
                                <div title="{{ $user[$value['column_name']] }}">
                                    <p class="rounded-md bg-yellow-400 px-2 py-0 text-xs font-semibold leading-tight text-red-400 dark:bg-red-500 dark:text-green-100">
                                        None Set
                                    </p>
                                </div>
                                @break

                                @case('attachment')
                                @if ($model === 'App\Models\User')
                                <div title="{{ $user[$value['column_name']] }}">

                                    <x-renderer.attachment attachment="{{ $user->id }}" model="{{ $model }}" relationship="{{ $item['relationship'] }}" />
                                </div>
                                @else
                                Render Failed
                                @endif
                                @break

                                @case('count')
                                <div title="{{ $user[$value['column_name']] }}">
                                    @isset($user->{$item['relationship']})
                                    <x-renderer.tag value="{{ !is_array($user->{$item['relationship']}) ? $user->{$item['relationship']}->count() : '0' }} items" />
                                    @else
                                    Render Failed
                                    @endisset
                                </div>
                                @break

                                @case('column')
                                @php
                                $render = $user->{$item['relationship']}->{$item['renderer_param']} ?? '';
                                @endphp
                                <div title="{{ $user[$value['column_name']] }}">
                                    <span class="text-sm">{{ $render }}</span>
                                </div>
                                @break

                                @case('avatar_name')
                                @if ($user->{$item['relationship']} != null && is_array($user->{$item['relationship']}))
                                <div title="{{ $user[$value['column_name']] }}">
                                    <x-renderer.user src="https://wp.tlcmodular.com/wp-content/uploads/2022/07/bfdc18a057769428cd67-150x150.jpg" name="{{ $user->{$item['relationship']}->name }}" email="{{ $user->{$item['relationship']}->email }}" />
                                </div>
                                @else
                                @php
                                $users = $user->{$item['relationship']};
                                @endphp
                                <div title="{{ $user[$value['column_name']] }}">
                                    <x-renderer.users :users="$users" />
                                </div>
                                @endif
                                @break

                                @default
                                <div title="{{ $user[$value['column_name']] }}">
                                    {{ $item['renderer'] }}
                                </div>
                                @endswitch
                                @else
                                {{ $user[$value['column_name']] }}
                                @endif
                                @endif
                            </td>
                            @endif
                            @endforeach
                            @isset($data2)
                            @foreach ($data2 as $key2 => $item)
                            @if ($item['hidden'] === null)
                            @switch($item['renderer'])
                            @case('')
                            <td class="text-center">
                                <p class="rounded-md bg-yellow-400 px-2 py-0 text-xs font-semibold leading-tight text-red-400 dark:bg-red-500 dark:text-green-100">
                                    None Set
                                </p>
                            </td>
                            @break

                            @case('attachment')
                            <td class="text-center">
                                @if ($model === 'App\Models\User')
                                <x-renderer.attachment attachment="{{ $user->id }}" model="{{ $model }}" relationship="{{ $item['relationship'] }}" />
                                @else
                                <p class="rounded-md bg-red-300 px-2 py-0 text-xs font-semibold leading-tight text-red-400 dark:bg-red-500 dark:text-green-100">
                                    Render Failed
                                </p>
                                @endif

                            </td>
                            @break

                            @case('count')
                            <td>
                                @isset($user->{$item['relationship']})
                                <x-renderer.tag value="{{ !is_array($user->{$item['relationship']}) ? $user->{$item['relationship']}->count() : '0' }} items" />
                                @else
                                <p class="rounded-md bg-red-300 px-2 py-0 text-xs font-semibold leading-tight text-red-400 dark:bg-red-500 dark:text-green-100">
                                    Render Failed
                                </p>
                                @endisset
                            </td>
                            @break

                            @case('column')
                            <td class="text-center">
                                @php
                                $render = $user->{$item['relationship']}->{$item['renderer_param']} ?? '';
                                @endphp
                                <span class="text-sm">{{ $render }}</span>
                            </td>
                            @break

                            @case('avatar_name')
                            <td class="px-4 py-3 text-center">
                                @if ($user->{$item['relationship']} != null && is_array($user->{$item['relationship']}))
                                <x-renderer.user src="https://wp.tlcmodular.com/wp-content/uploads/2022/07/bfdc18a057769428cd67-150x150.jpg" name="{{ $user->{$item['relationship']}->name }}" email="{{ $user->{$item['relationship']}->email }}" />
                                @else
                                @php
                                $users = $user->{$item['relationship']};
                                @endphp
                                <x-renderer.users :users="$users" />
                                @endif
                            </td>
                            @break

                            @default
                            <td class="px-4 py-3 text-center">
                                {!! $item['renderer'] ??
                                '<p class="rounded-md bg-red-300 px-2 py-0 text-xs font-semibold leading-tight text-red-400 dark:bg-red-500 dark:text-green-100">
                                    Render Failed </p>' !!}
                            </td>
                            @endswitch
                            @endif
                            @endforeach
                            @endisset
                        </tr>
                        @endforeach
                        @endif
                        @endisset
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
                        {{ $users->links('dashboards.pagination.template') }}
                        @endif
                    </nav>
                </span>
            </div>
        </div>
        @endempty
    </div>
</main>
<x-modalsetting :type="$type" />
<script src="{{ asset('js/renderprop.js') }}"></script>
@endsection
