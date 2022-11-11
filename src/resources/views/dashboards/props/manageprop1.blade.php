@extends('layouts.app')

@section('title', 'Manage Props')

@section('content')
<form action="{{ route($type . '_mngprop.store') }}" method="POST">
    @csrf
    <div class="mt-2 mb-8 w-full overflow-hidden rounded-lg border shadow-sm bg-white dark:bg-gray-800 ">
        <div class="w-full overflow-x-auto">

            <table class="whitespace-no-wrap w-full">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        <th class="px-4 py-3 text-center">No.</th>
                        <th class="px-4 py-3 text-center">Name</th>
                        <th class="px-4 py-3 text-center">Column Name</th>
                        <th class="px-4 py-3 text-center">Column Type</th>
                        <th class="px-4 py-3 text-center">Label</th>
                        <th class="px-4 py-3 text-center">Control</th>
                        <th class="px-4 py-3 text-center">Col span</th>
                        <th class="px-4 py-3 text-center">Hidden View All</th>
                        <th class="px-4 py-3 text-center">Hidden Edit</th>
                        <th class="px-4 py-3 text-center">New Line</th>
                        <th class="px-4 py-3 text-center">Validation</th>
                        <th class="px-4 py-3 text-center">Frozen Left</th>
                        <th class="px-4 py-3 text-center">Frozen Right</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                    @php
                    try {
                    $controls = json_decode(file_get_contents(storage_path() . '/json/configs/view/dashboard/props/controls.json'), true)['controls'];
                    } catch (\Throwable $th) {
                    $errorsLineProp = "Setting Prop Control json fail!
                    Please fix file controls.json before Run.";
                    }
                    @endphp
                    @isset($errorsLineProp)
                    <x-feedback.alert title="Warning Settings" message="{{ $errorsLineProp }}" />
                    @else
                    @isset($names)
                    @php
                    $number = 1;
                    @endphp
                    @foreach ($names as $key => $name)
                    <tr class="table-line-{{ $colorLines[$key] }} text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3 text-center text-sm">{{ $number }}</td>
                        @php
                        $number++;
                        @endphp
                        <td class="px-4 py-3 text-center text-sm">
                            {{ $name }}
                            <input type="text" name="name[]" value="{{ $name }}" readonly hidden>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            {{ $columnNames[$key] ?? '' }}
                            <input type="text" name="column_name[]" value="{{ $columnNames[$key] ?? '' }}" readonly hidden>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            {{ $columnTypes[$key] ?? '' }}
                            <input type="text" name="column_type[]" value="{{ $columnTypes[$key] ?? '' }}" readonly hidden>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <input type="text" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" name="label[]" value="{{ ucwords(str_replace('_', ' ', $columnLabels[$key])) }}">
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <select name="control[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                @foreach ($controls as $control)
                                @if ($columnControls[$key] === $control)
                                <option value="{{ $control }}" selected>
                                    {{ ucfirst($control) }}
                                </option>
                                @else
                                <option value="{{ $control }}">{{ ucfirst($control) }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <input type="text" class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" name="col_span[]" value="{{ $columnColSpans[$key] ?? '' }}">
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <select name="hidden_view_all[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                <option value="" @if ($columnHiddenViewAlls[$key]=='' ) selected @endif>
                                </option>
                                <option value="true" @if ($columnHiddenViewAlls[$key]==='true' ) selected @endif>
                                    True</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <select name="hidden_edit[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                <option value="" @if ($columnHiddenEdits[$key]=='' ) selected @endif>
                                </option>
                                <option value="true" @if ($columnHiddenEdits[$key]==='true' ) selected @endif>
                                    True</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <select name="new_line[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                <option value="" @if ($columnNewLines[$key]=='' ) selected @endif>
                                </option>
                                <option value="true" @if ($columnNewLines[$key]==='true' ) selected @endif>
                                    True</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <input type="text" class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" name="validation[]" value="{{ $columnValidations[$key] ?? '' }}">
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <select name="frozen_left[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                <option value="" @if ($columnFrozenLefts[$key]=='' ) selected @endif>
                                </option>
                                <option value="true" @if ($columnFrozenLefts[$key]==='true' ) selected @endif>
                                    True</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <select name="frozen_right[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                <option value="" @if ($columnFrozenRights[$key]=='' ) selected @endif>
                                </option>
                                <option value="true" @if ($columnFrozenRights[$key]==='true' ) selected @endif>
                                    True</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            @if ($colorLines[$key] == 'removed')
                            <button class="btn btn-danger btn-delete" data-url="{{ route($type . '_mngprop.destroy', $name) }}" ​ type="button"><i class="fas fa-trash"></i></button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    @foreach ($columnNames as $key => $columnName)
                    <tr class="table-line-new text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3 text-center text-sm">{{ $key + 1 }}</td>
                        <td class="px-4 py-3 text-center text-sm">
                            _{{ $columnName }}
                            <input type="text" name="name[]" class="form-control" value="_{{ $columnName }}" readonly hidden>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            {{ $columnName }}
                            <input type="text" name="column_name[]" class="form-control" value="{{ $columnName }}" readonly hidden>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            {{ $columnTypes[$key] ?? '' }}
                            <input type="text" name="column_type[]" class="form-control" value="{{ $columnTypes[$key] ?? '' }}" readonly hidden>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <input type="text" name="label[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="{{ ucwords(str_replace('_', ' ', $columnName)) }}">
                        </td>

                        <td class="px-4 py-3 text-center text-sm">
                            <select name="control[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                @foreach ($controls as $control)
                                <option value="{{ $control }}">{{ ucfirst($control) }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <input type="text" name="col_span[]" class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="12">
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <select name="hidden_view_all[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                <option value=""></option>
                                <option value="true">True</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <select name="hidden_edit[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                <option value=""></option>
                                <option value="true">True</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <select name="new_line[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                <option value=""></option>
                                <option value="true">True</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <input type="text" class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" name="validation[]" value="">
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <select name="frozen_left[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                <option value=""></option>
                                <option value="true">True</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            <select name="frozen_right[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                <option value=""></option>
                                <option value="true">True</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-center text-sm">
                            @if (isset($colorLines))
                            <button class="btn btn-danger btn-delete" data-url="{{ route('mngprop.destroy', $name) }}" ​ type="button"><i class="fas fa-trash"></i></button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endisset
                    @endisset
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
        <button class="focus:shadow-outline-purple my-2 ml-2 rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-400 focus:outline-none active:bg-emerald-600" type="submit">Update</button>
    </div>
</form>
@endsection