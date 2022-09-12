<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="grid grid-rows-1 bg-gray-300">
        <div class="mx-auto flex min-h-screen items-center py-5">
            <form class="rounded-md bg-white p-4" method='POST' action="{{ route('user_edit.update', $values->id) }} ">
                @csrf
                <div class="mx-auto max-w-4xl flex-1">
                    <div class="grid grid-cols-12">
                        @method('PUT')
                        @foreach ($props as $key => $value)
                            @php
                                $label = $value['label'];
                                $col_span = $value['col_span'];
                                $column_name = $value['column_name'];
                                $value_column_name = $values->{$column_name};
                                if (is_array($value_column_name)) {
                                    $value_column_name = 'ARRAY';
                                }
                                // dd($value_column_name);
                                $col_span = $value['col_span'];
                            @endphp
                            <div class='col-span-{{ $col_span }}'>
                                <div class='grid-row-1 grid gap-3'>
                                    <div class='grid grid-cols-12 items-center'>
                                        <div class='col-span-{{ 24 / $col_span }} col-start-1 text-right'>
                                            <label class='mb-2 block px-3 text-xs text-base tracking-wide text-gray-800'
                                                title='{{ $column_name }}'>{{ $label }}
                                            </label>
                                        </div>

                                        <div class='col-start-{{ 24 / $col_span + 1 }} col-span-10'>
                                            <input name='{{ $column_name }}'
                                                class='ppearance-none mb-3 block w-full rounded border bg-gray-200 py-3 px-4 leading-tight text-gray-700 focus:bg-white focus:outline-none'
                                                type='text' value='{{ $value_column_name }}'>
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
                        <button
                            class="focus:shadow-outline rounded bg-purple-500 py-2 px-4 font-bold text-white shadow hover:bg-purple-400 focus:outline-none"
                            type="button">
                            Update
                        </button>
                    </div>
                </div>
        </div>
        </form>
    </div>

    </div>

</body>

</html>
