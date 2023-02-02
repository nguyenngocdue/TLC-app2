li@extends('layouts.app')

@section('topTitle', $topTitle)
@section('title', $title)

@section('content')

    @php
        $editType = Str::plural($type);
        $labelValidation = '';
        $id = $action === 'edit' ? $values->id : '';
        
    @endphp

    {{-- HERE {{mb_strlen(serialize((array)$listenerDataSource), '8bit');}} bytes --}}
    <script>
        k = @json($listenerDataSource);
        listeners = @json($listeners);
        filters = @json($filters);
    </script>


    @if ($action === 'edit')
        <a class="text-blue-500 hover:text-gray-400" href="{{ route($editType . '.show', $id) }}">show</a></li>
    @endif

    <x-controls.header-alert-validation :strProps="$props" />
    <form class="mb-8 w-full rounded-lg bg-white dark:bg-gray-800" id="form-upload" method="POST" enctype="multipart/form-data"
        action="{{ route($action === 'create' ? $editType . '.store' : $editType . '.update', $action === 'create' ? 0 : $id) }} ">
        @csrf
        <div class="grid grid-cols-12">
            @method($action === 'create' ? 'POST' : 'PUT')
            @php
                $timeControls = ['picker_time', 'picker_date', 'picker_month', 'picker_week', 'picker_quarter', 'picker_year', 'picker_datetime'];
            @endphp
            @foreach ($props as $key => $val)
                @php
                    if ($action === 'create' && $val['control'] === 'relationship_renderer') {
                        continue;
                    }
                    $defaultValue = $defaultValues[$key] ?? [];
                    
                    $label = $val['label'];
                    $columnName = $val['column_name'];
                    $columnType = $val['column_type'];
                    $align = $val['align'] ?? 'left';
                    $control = $val['control'];
                    
                    $colSpan = $val['col_span'];
                    $value = $action === 'edit' ? $values->{$columnName} ?? '' : '';
                    $title = $columnName . ' / ' . $control;
                    $col_span = $val['col_span'] === '' ? 1 : $val['col_span'] * 1;
                    $hiddenRow = $props[$key]['hidden_edit'] === 'true' ? 'hidden' : '';
                    
                    $isRequired = in_array('required', explode('|', $defaultValue['validation'] ?? ''));
                    $iconJson = $columnType === 'json' ? '<i class="fa-duotone fa-brackets-curly"></i>' : '';
                @endphp
                <div class='col-span-{{ $col_span }} grid'>
                    <div class='grid-row-1 grid'>
                        <div class='{{ $hiddenRow }} grid grid-cols-12 items-center'>
                            @if ($columnType === 'static')
                                <div class='col-span-12 text-left'>
                                    @switch($control)
                                        @case('z_page_break')
                                            <x-renderer.page-break />
                                        @case('z_h1')
                                            <x-renderer.heading title="{{ $title }}" level=1 align="{{ $align }}">
                                                {{ $label }}</x-renderer.heading>
                                        @break

                                        @case('z_h2')
                                            <x-renderer.heading title="{{ $title }}" level=2 align="{{ $align }}">
                                                {{ $label }}</x-renderer.heading>
                                        @break

                                        @case('z_h3')
                                            <x-renderer.heading title="{{ $title }}" level=3 align="{{ $align }}">
                                                {{ $label }}</x-renderer.heading>
                                        @break

                                        @case('z_h4')
                                            <x-renderer.heading title="{{ $title }}" level=4 align="{{ $align }}">
                                                {{ $label }}</x-renderer.heading>
                                        @break

                                        @case('z_h5')
                                            <x-renderer.heading title="{{ $title }}" level=5 align="{{ $align }}">
                                                {{ $label }}</x-renderer.heading>
                                        @break

                                        @case('z_h6_base')
                                            <x-renderer.heading title="{{ $title }}" align="{{ $align }}">
                                                {{ $label }}</x-renderer.heading>
                                        @break

                                        @case('z_divider')
                                            <x-renderer.divider />
                                        @break

                                        @default
                                            <x-feedback.alert type="warning" title="{{ $title }}"
                                                message="[{{ $control }}] is not available" />
                                        @break
                                    @endswitch
                                </div>
                            @else
                                <div
                                    class='col-span-{{ 24 / $col_span }} {{ $val['new_line'] === 'true' ? 'col-span-12 text-left' : 'text-right' }} col-start-1'>
                                    <label class='block px-3 text-base text-gray-700 dark:text-gray-400'
                                        title='{{ $title }}'>{{ $label }}
                                        {!! $isRequired ? "<span class='text-red-400'>*</span>" : '' !!}
                                        <br />
                                        <span class="flex justify-end">
                                            {!! $iconJson !!}
                                        </span>
                                    </label>
                                </div>
                                <div
                                    class='col-start-{{ 24 / $col_span + 1 }} {{ $val['new_line'] === 'true' ? 'col-span-12' : 'col-span-' . (12 - 24 / $col_span) }} py-2 text-left'>
                                    @if (is_null($control))
                                        <h2 class="text-red-400">{{ "Control of this $columnName has not been set" }}</h2>
                                    @endif

                                    {{-- Invisible anchor for scrolling when users click on validation fail message --}}
                                    <strong class="snap-start scroll-mt-20" id="scroll-{{ $columnName }}"></strong>

                                    @switch ($control)
                                        @case($timeControls[0])
                                        @case($timeControls[1])

                                        @case($timeControls[2])
                                        @case($timeControls[3])

                                        @case($timeControls[4])
                                        @case($timeControls[5])

                                        @case($timeControls[6])
                                            <x-controls.date-time name={{ $columnName }} value={{ $value }}
                                                control="{{ $control }}" />
                                            <x-controls.alert-validation2 name={{ $columnName }} label={{ $label }} />
                                        @break

                                        @case('id')
                                            <x-controls.id name={{ $columnName }}
                                                value="{{ $action === 'edit' ? $value : 'to be generated' }}" />
                                        @break

                                        @case('text')
                                            <x-controls.text name={{ $columnName }} value={{ $value }} />
                                            <x-controls.alert-validation2 name={{ $columnName }} label={{ $label }} />
                                        @break

                                        @case('number')
                                            <x-controls.number name={{ $columnName }} value={{ $value }} />
                                            <x-controls.alert-validation2 name={{ $columnName }} label={{ $label }} />
                                        @break

                                        @case('textarea')
                                            <x-controls.textarea name={{ $columnName }} :value="$value"
                                                colType={{ $columnType }} />
                                            <x-controls.alert-validation2 name={{ $columnName }} label={{ $label }} />
                                        @break

                                        @case('toggle')
                                            <x-controls.toggle name={{ $columnName }} value={{ $value }} />
                                            <x-controls.alert-validation2 name={{ $columnName }} label={{ $label }} />
                                        @break

                                        @case('status')
                                            <x-controls.control-status value={{ $value }} name={{ $columnName }}
                                                modelPath={{ $modelPath }} />
                                        @break

                                        @case ('dropdown')
                                            <x-controls.has-data-source.dropdown type={{ $type }} name={{ $columnName }}
                                                selected={{ $value }} id={{ $id }} colName={{ $columnName }}
                                                modelPath={{ $modelPath }} label={{ $label }} />
                                            {{-- <x-controls.has-data-source.dropdown type={{$type}} name={{$columnName}} selected={{$value}} id={{$id}} colName={{$columnName}} modelPath={{$modelPath}} label={{$label}} /> --}}
                                        @break

                                        @case ('radio')
                                            <x-controls.has-data-source.radio type={{ $type }} name={{ $columnName }}
                                                selected={{ $value }} id={{ $id }} colName={{ $columnName }}
                                                modelPath={{ $modelPath }} label={{ $label }} />
                                        @break

                                        @case ('dropdown_multi')
                                            <x-controls.has-data-source.dropdown type={{ $type }} name={{ $columnName }}
                                                selected={{ $value }} multiple={{ true }} id={{ $id }}
                                                colName={{ $columnName }} modelPath={{ $modelPath }}
                                                label={{ $label }} />
                                        @break

                                        @case('checkbox')
                                            <x-controls.has-data-source.checkbox type={{ $type }} name={{ $columnName }}
                                                selected={{ $value }} id={{ $id }} colName={{ $columnName }}
                                                modelPath={{ $modelPath }} label={{ $label }} />
                                        @break

                                        @case('attachment')
                                            <x-controls.upload-files id={{ $id }} colName={{ $columnName }}
                                                label={{ $label }} type={{ $type }} />
                                        @break

                                        @case('comment')
                                            <x-controls.comment-group id={{ $id }} type={{ $type }}
                                                colName={{ $columnName }} label={{ $label }}
                                                colSpan={{ $col_span }} />
                                        @break

                                        @case('relationship_renderer')
                                            <x-controls.relationship-renderer id={{ $id }} type={{ $type }}
                                                colName={{ $columnName }} modelPath={{ $modelPath }}
                                                colSpan={{ $col_span }} />
                                        @break

                                        @default
                                            <x-feedback.alert type="warning" title="Control"
                                                message="[{{ $control }}] is not available" />
                                        @break
                                    @endswitch

                                    @switch ($action)
                                        @case('edit')
                                            <x-controls.localtime id={{ $id }} control={{ $control }}
                                                colName={{ $columnName }} modelPath={{ $modelPath }} :timeControls="$timeControls"
                                                label={{ $label }} />
                                        @break
                                    @endswitch
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach

        </div>
        <div class="justify-left flex border-t-2 px-5 dark:bg-gray-800">
            @switch($action)
                @case('edit')
                    <button type="submit"
                        class="focus:shadow-outline mt-4 rounded bg-emerald-500 py-2 px-4 font-bold text-white hover:bg-purple-400 focus:outline-none">
                        Update
                    </button>
                @break

                @case('create')
                    <button type="submit"
                        class="focus:shadow-outline mt-4 rounded bg-emerald-500 py-2 px-4 font-bold text-white hover:bg-purple-400 focus:outline-none">
                        Create
                    </button>
                @break

                @default
                    <span>Unknown action "{{ $action }}"</span>
                @break
            @endswitch
        </div>
    </form>
@endsection
