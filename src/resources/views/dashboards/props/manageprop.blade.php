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
                    <span>{{ ucfirst($type) }}</span>
                </div>
                <span>Manage Prop -></span>
            </div>
            <form action="{{ route($type . '_mngprop.store') }}" method="POST">
                @csrf
                <div class="mt-2 mb-8 w-full overflow-hidden rounded-lg border shadow-sm">
                    <div class="w-full overflow-x-auto">
                        <table class="whitespace-no-wrap w-full">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                    <th class="px-4 py-3">No.</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Column Name</th>
                                    <th class="px-4 py-3">Column Type</th>
                                    <th class="px-4 py-3">Label</th>
                                    <th class="px-4 py-3">Control</th>
                                    <th class="px-4 py-3">Col span</th>
                                    <th class="px-4 py-3">Hidden</th>
                                    <th class="px-4 py-3">New Line</th>
                                    <th class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                                @php
                                    $controls = json_decode(file_get_contents(storage_path() . '/json/configs/view/dashboard/props/controls.json'), true)['controls'];
                                @endphp
                                @if (isset($names))
                                    @php
                                        $number = 1;
                                    @endphp
                                    @foreach ($names as $key => $name)
                                        <tr class="table-line-{{ $colorLines[$key] }} text-gray-700 dark:text-gray-400">
                                            <td class="px-4 py-3 text-sm">{{ $number }}</td>
                                            @php
                                                $number++;
                                            @endphp
                                            <td class="px-4 py-3 text-sm">
                                                {{ $name }}
                                                <input type="text" name="name[]" value="{{ $name }}" readonly
                                                    hidden>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $columnNames[$key] }}
                                                <input type="text" name="column_name[]" value="{{ $columnNames[$key] }}"
                                                    readonly hidden>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $columnTypes[$key] }}
                                                <input type="text" name="column_type[]" value="{{ $columnTypes[$key] }}"
                                                    readonly hidden>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <input type="text"
                                                    class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm"
                                                    name="label[]"
                                                    value="{{ ucwords(str_replace('_', ' ', $columnLabels[$key])) }}">
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <select name="control[]"
                                                    class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                                    @foreach ($controls as $control)
                                                        @if ($columnControls[$key] === $control)
                                                            <option value="{{ $control }}" selected>
                                                                {{ ucfirst($control) }}</option>
                                                        @else
                                                            <option value="{{ $control }}">{{ ucfirst($control) }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <input type="text"
                                                    class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm"
                                                    name="col_span[]" value="{{ $columnColSpans[$key] }}">
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <select name="hidden[]"
                                                    class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                                    <option value=""
                                                        @if ($columnHidden[$key] == '') selected @endif></option>
                                                    <option value="true"
                                                        @if ($columnHidden[$key] === 'true') selected @endif>True</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <select name="new_line[]"
                                                    class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                                    <option value=""
                                                        @if ($columnNewLines[$key] == '') selected @endif></option>
                                                    <option value="true"
                                                        @if ($columnNewLines[$key] === 'true') selected @endif>True</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm">
                                                @if ($colorLines[$key] == 'removed')
                                                    <button class="btn btn-danger btn-delete"
                                                        data-url="{{ route($type . '_mngprop.destroy', $name) }}"​
                                                        type="button"><i class="fas fa-trash"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($columnNames as $key => $columnName)
                                        <tr class="table-line-new text-gray-700 dark:text-gray-400">
                                            <td class="px-4 py-3 text-sm">{{ $key + 1 }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                _{{ $columnName }}
                                                <input type="text" name="name[]" class="form-control"
                                                    value="_{{ $columnName }}" readonly hidden>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $columnName }}
                                                <input type="text" name="column_name[]" class="form-control"
                                                    value="{{ $columnName }}" readonly hidden>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                {{ $columnTypes[$key] }}
                                                <input type="text" name="column_type[]" class="form-control"
                                                    value="{{ $columnTypes[$key] }}" readonly hidden>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <input type="text" name="label[]"
                                                    class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm"
                                                    value="{{ ucwords(str_replace('_', ' ', $columnName)) }}">
                                            </td>

                                            <td class="px-4 py-3 text-sm">
                                                <select name="control[]"
                                                    class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                                    @foreach ($controls as $control)
                                                        <option value="{{ $control }}">{{ ucfirst($control) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <input type="text" name="col_span[]"
                                                    class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm"
                                                    value="12">
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <select name="hidden[]"
                                                    class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                                    <option value=""></option>
                                                    <option value="true">True</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <select name="new_line[]"
                                                    class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                                    <option value=""></option>
                                                    <option value="true">True</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm">
                                                @if (isset($colorLines))
                                                    <button class="btn btn-danger btn-delete"
                                                        data-url="{{ route('mngprop.destroy', $name) }}"​
                                                        type="button"><i class="fas fa-trash"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <button
                        class="focus:shadow-outline-purple my-2 ml-2 rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-emerald-200 focus:outline-none active:bg-emerald-600"
                        type="submit">Update</button>
                </div>
            </form>
        </div>
    </main>
@endsection
