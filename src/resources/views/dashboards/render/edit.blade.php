<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class=" grid grid-rows-1">
        <form class="" method='POST' action="{{route('user_edit.update',$values->id)}} ">
            @csrf
            <div class="min-h-screen flex items-center bg-purple-500">
                <div class="flex-1 max-w-4xl mx-auto">
                    <div class="grid grid-cols-12 gap-4">
                        @method('PUT')                             
                        @foreach($props as $key => $value)
                            @php
                            $label = $value['label'];
                            $col_span = $value['col_span'];
                            $column_name = $value['column_name'];
                            // dd($column_name);
                            $value_column_name = $values->{$column_name};
                            $col_span=$value['col_span'];
                            @endphp
                        {{-- {{dd($value['col_span'])}} --}}
                        {{-- {!! $col_span === "12" ? --}}
                        <div class='col-span-{{$col_span}}'>
                            <div class='grid grid-row-1 gap-3'>
                                <div class='grid grid-cols-12 items-center'>
                                    <div class='col-start-1 col-span-2'>
                                        <label 
                                            class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' 
                                            title='{{$column_name}}'
                                            >$label:
                                        </label>
                                    </div>
                                    <div class='col-start-3 col-span-10'>
                                        <input 
                                            name='{{$column_name}}' 
                                            class='ppearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white' 
                                            type='text' 
                                            value='{{$value_column_name}}'>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
    </div>

    </form>
    </div>

</body>
</html>

