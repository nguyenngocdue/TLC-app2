<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src={{asset('js/app.js')}}></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/themes/airbnb.min.css">
    <link rel="stylesheet" href="https://www.tailwindcsscomponent.com/date-and-time-picker#the-description-of-date-and-time-picker-ui-component">
    {{-- Multilselect dropdown --}}
    <link rel="stylesheet" href="{{asset('css/customize.css')}}">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>




</head>

<body>
    <div class="grid grid-rows-1 bg-gray-300">
        <div class="mx-auto flex min-h-screen items-center py-5">
            <form class="rounded-md bg-white p-4" method='POST' action="{{ route('user_edit.update', $values->id) }} ">
                @csrf
                <div class="mx-auto max-w-4xl flex-1">
                    <div class="grid grid-cols-12">
                        @method('PUT')
                        @foreach($props as $key => $value)
                        @php
                        $label = $value['label'];
                        $col_span = $value['col_span'];
                        $column_name = $value['column_name'];
                        $control = $value['control'];
                        $value_column_name = $values->{$column_name};
                        if(is_array($value_column_name)) $value_column_name = 'ARRAY';
                        $col_span=$value['col_span'];
                        $id= $values->id;
                        @endphp

                        <div class='col-span-{{$col_span}}'>
                            <div class='grid grid-row-1 gap-3'>
                                <div class='grid grid-cols-12 items-center'>
                                    <div class='col-start-1 col-span-{{24/$col_span}} text-right'>
                                        <label class='block tracking-wide text-gray-800 text-xs mb-2 px-3 text-base' title='{{$column_name}}'>{{$label}}
                                        </label>
                                    </div>
                                    <div class='col-start-{{24/$col_span + 1}} col-span-10 py-2'>

                                        @switch ($control)
                                        {{-- @case ('text')
                                        <x-controls.text columnName={{$column_name}} valColName={{$value_column_name}} />
                                        @break --}}

                                        @case('picker_time')
                                        @case('picker_date')
                                        @case('picker_month')
                                        @case('picker_week')
                                        @case('picker_quater')
                                        @case('picker_year')
                                        @case('datetime')
                                        <x-controls.text columnName={{$column_name}} valColName={{$value_column_name}} />
                                        <x-controls.translationtime id={{$id}} control={{$control}} columnName={{$column_name}} />
                                        @break

                                        @case ('dropdown')
                                        <x-controls.dropdown id={{$id}} />
                                        @break

                                        @case ('radio')
                                        <x-controls.radio id={{$id}} colName={{$column_name}} />
                                        @break

                                        @case ('tag')
                                        <x-controls.tag id={{$id}} colName={{$column_name}} />
                                        @break

                                        @case ('checkbox')
                                        <x-controls.checkbox id={{$id}} colName={{$column_name}} />
                                        @break

                                        @default
                                        {{$control}}
                                        @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
                <div class="flex justify-end px-5">
                    <div></div>
                    <div class="">
                        <button type="submit" class="focus:shadow-outline rounded bg-purple-500 py-2 px-4 font-bold text-white shadow hover:bg-purple-400 focus:outline-none" type="button">
                            Update
                        </button>
                    </div>
                </div>
        </div>
        </form>
    </div>

</body>

</html>
