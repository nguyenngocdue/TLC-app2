@extends('layouts.app')

@section('content')
<main class="h-full overflow-y-auto">
    <div class="container mx-auto grid px-6">
        <div class="focus:shadow-outline-purple my-4 flex items-center justify-between rounded-lg bg-purple-600 p-3 text-base font-semibold text-purple-100 shadow-md focus:outline-none">
            <div class="flex items-center">
                <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                    </path>
                </svg>
                <span>{{ ucfirst($type) }}</span>
            </div>
            <span>Relationship -></span>
        </div>
        <x-controls.breadcrumb type={{$type}} />
        <form action="{{ route($type . '_mngrls.store') }}" method="POST">
            @csrf
            <div class="mt-2 mb-8 w-full overflow-hidden rounded-lg border shadow-sm bg-white dark:bg-gray-800 ">
                <div class="w-full overflow-x-auto">
                    <table id="table_manage" class="whitespace-no-wrap w-full">
                        <thead>
                            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                <th class="px-4 py-3 text-center">No.</th>
                                <th class="px-4 py-3 text-center">Name</th>
                                <th class="px-4 py-3 text-center">Relationship</th>
                                <th class="px-4 py-3 text-center">Label</th>
                                <th class="px-4 py-3 text-center">Renderer</th>
                                <th class="px-4 py-3 text-center">Renderer Param</th>
                                <th class="px-4 py-3 text-center">Control Name</th>
                                <th class="px-4 py-3 text-center">Col span</th>
                                <th class="px-4 py-3 text-center">Hidden</th>
                                <th class="px-4 py-3 text-center">New Line</th>
                                <th class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @php
                            try {
                            $renderers = json_decode(file_get_contents(storage_path() . '/json/configs/view/dashboard/relationships/renderers.json'), true)['renderers'];
                            } catch (\Throwable $th) {
                            $errorsLineProp = "Setting Relationship Control json fail!
                            Please fix file renderers.json before Run.";
                            }
                            @endphp
                            @isset($errorsLineProp)
                            <x-render.warningfix title="Warning Settings" warning="{{ $errorsLineProp }}" />
                            @else
                            @isset($names)
                            @php
                            $number = 1;
                            @endphp
                            @foreach ($names as $key => $name)
                            <tr class="table-line-{{ $colorLines[$key] ?? '' }} text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-center text-sm">{{ $number }}</td>
                                @php
                                $number++;
                                @endphp
                                <td class="px-4 py-3 text-center text-sm">
                                    {{ $name }}
                                    <input type="text" name="name[]" value="{{ $name }}" readonly hidden>
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    {{ $columnRelationships[$key] ?? '' }}
                                    <input type="text" name="relationship[]" value="{{ $columnRelationships[$key] ?? '' }}" readonly hidden>
                                    <input type="text" name="eloquent[]" value="{{ $columnEloquents[$key] ?? '' }}" readonly hidden>
                                    <input type="text" name="param_1[]" value="{{ $columnParam1s[$key] ?? '' }}" readonly hidden>
                                    <input type="text" name="param_2[]" value="{{ $columnParam2s[$key] ?? '' }}" readonly hidden>
                                    <input type="text" name="param_3[]" value="{{ $columnParam3s[$key] ?? '' }}" readonly hidden>
                                    <input type="text" name="param_4[]" value="{{ $columnParam4s[$key] ?? '' }}" readonly hidden>
                                    <input type="text" name="param_5[]" value="{{ $columnParam5s[$key] ?? '' }}" readonly hidden>
                                    <input type="text" name="param_6[]" value="{{ $columnParam6s[$key] ?? '' }}" readonly hidden>
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <input type="text" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" name="label[]" value="{{ ucwords(str_replace('_', ' ', $columnLabels[$key])) }}">
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <select name="renderer[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                        @php
                                        $rendererIndex;
                                        @endphp
                                        @foreach ($renderers as $renderer)
                                        @if ($columnRenderers[$key] === $renderer)
                                        <option value="{{ $renderer }}" selected>
                                            {{ ucfirst($renderer) }}
                                        </option>
                                        @php
                                        $rendererIndex = $renderer;
                                        @endphp
                                        @else
                                        <option value="{{ $renderer }}">{{ ucfirst($renderer) }}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <input type="text" class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" name="renderer_param[]" value="{{ $columnRendererParams[$key] ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" name="control_name[]" value="{{ $columnControlNames[$key] ?? '' }}">

                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <input type="text" class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" name="col_span[]" value="{{ $columnColSpans[$key] ?? '' }}">
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <select name="hidden[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                        <option value="" @if ($columnHidden[$key]=='' ) selected @endif>
                                        </option>
                                        <option value="true" @if ($columnHidden[$key]==='true' ) selected @endif>
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
                                <td class="px-4 py-3 text-center text-center text-sm">
                                    @if ($colorLines[$key] == 'removed')
                                    <button class="btn btn-danger btn-delete" data-url="{{ route($type . '_mngrls.destroy', $name) }}" ​ type="button"><i class="fas fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="bg-gray-100 py-1"></td>
                                <td colspan="8" class="bg-gray-100 py-1">
                                    @php
                                    $renderRelationship = [];
                                    array_push($renderRelationship, $columnEloquents[$key], $columnParam1s[$key], $columnParam2s[$key], $columnParam3s[$key], $columnParam4s[$key], $columnParam5s[$key], $columnParam6s[$key]);
                                    $render = array_filter($renderRelationship, fn($item) => $item);
                                    $render = join(' | ', $render);
                                    @endphp
                                    <p class="text-xs text-gray-600">
                                        {{ $render }}
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            @foreach ($columnRelationships as $key => $columnRelationship)
                            <tr class="table-line-new">
                                <td class="px-4 py-3 text-center text-sm">{{ $key + 1 }}</td>
                                <td class="px-4 py-3 text-center text-sm">
                                    _{{ $columnRelationship }}
                                    <input type="text" name="name[]" value="_{{ $columnRelationship }}" readonly hidden>
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    {{ $columnRelationship }}
                                    <input type="text" name="relationship[]" value="{{ $columnRelationship }}" readonly hidden>
                                    <input type="text" name="eloquent[]" value="{{ isset($columnEloquentParams[$columnRelationship][0]) ? $columnEloquentParams[$columnRelationship][0] : '' }}" readonly hidden>
                                    <input type="text" name="param_1[]" value="{{ isset($columnEloquentParams[$columnRelationship][1]) ? $columnEloquentParams[$columnRelationship][1] : '' }}" readonly hidden>
                                    <input type="text" name="param_2[]" value="{{ isset($columnEloquentParams[$columnRelationship][2]) ? $columnEloquentParams[$columnRelationship][2] : '' }}" readonly hidden>
                                    <input type="text" name="param_3[]" value="{{ isset($columnEloquentParams[$columnRelationship][3]) ? $columnEloquentParams[$columnRelationship][3] : '' }}" readonly hidden>
                                    <input type="text" name="param_4[]" value="{{ isset($columnEloquentParams[$columnRelationship][4]) ? $columnEloquentParams[$columnRelationship][4] : '' }}" readonly hidden>
                                    <input type="text" name="param_5[]" value="{{ isset($columnEloquentParams[$columnRelationship][5]) ? $columnEloquentParams[$columnRelationship][5] : '' }}" readonly hidden>
                                    <input type="text" name="param_6[]" value="{{ isset($columnEloquentParams[$columnRelationship][6]) ? $columnEloquentParams[$columnRelationship][6] : '' }}" readonly hidden>
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <input type="text" name="label[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="{{ ucwords(str_replace('_', ' ', $columnRelationship)) }}">
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <select name="renderer[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
                                        @foreach ($renderers as $renderer)
                                        <option value="{{ $renderer }}">{{ ucfirst($renderer) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <input type="text" class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" name="renderer_param[]" value="">
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <input type="text" name="control_name[]" class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="">
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <input type="text" name="col_span[]" class="mt-1 block w-full max-w-fit rounded-md border border-slate-300 bg-white px-3 py-2 text-center placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm" value="12">
                                </td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <select name="hidden[]" class="mt-1 block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left placeholder-slate-400 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500 sm:text-sm">
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
                                <td class="px-4 py-3 text-center text-center text-sm">
                                    @isset($colorLines)
                                    <button class="btn btn-danger btn-delete" data-url="{{ route($type . '_mngrls.destroy', $name) }}" ​ type="button"><i class="fas fa-trash"></i></button>
                                    @endisset
                                </td>
                            </tr>
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="bg-gray-100 py-1"></td>
                                <td colspan="8" class="bg-gray-100 py-1">
                                    @php
                                    $renderRelationship = [];
                                    $var0 = isset($columnEloquentParams[$columnRelationship][0]) ? $columnEloquentParams[$columnRelationship][0] : '';
                                    $var1 = isset($columnEloquentParams[$columnRelationship][1]) ? $columnEloquentParams[$columnRelationship][1] : '';
                                    $var2 = isset($columnEloquentParams[$columnRelationship][2]) ? $columnEloquentParams[$columnRelationship][2] : '';
                                    $var3 = isset($columnEloquentParams[$columnRelationship][3]) ? $columnEloquentParams[$columnRelationship][3] : '';
                                    $var4 = isset($columnEloquentParams[$columnRelationship][4]) ? $columnEloquentParams[$columnRelationship][4] : '';
                                    $var5 = isset($columnEloquentParams[$columnRelationship][5]) ? $columnEloquentParams[$columnRelationship][5] : '';
                                    $var6 = isset($columnEloquentParams[$columnRelationship][6]) ? $columnEloquentParams[$columnRelationship][6] : '';
                                    array_push($renderRelationship, $var0, $var1, $var2, $var3, $var4, $var5, $var6);
                                    $render = array_filter($renderRelationship, fn($item) => $item);
                                    $render = join(' | ', $render);
                                    @endphp
                                    <p class="text-xs text-gray-600">
                                        {{ $render }}
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                            @endisset
                        </tbody>
                    </table>
                </div>
                <button class="focus:shadow-outline-purple my-2 ml-2 rounded-lg border border-transparent bg-emerald-500 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-400 focus:outline-none active:bg-emerald-600" type="submit">Update</button>
            </div>
        </form>

    </div>
</main>
@endsection
