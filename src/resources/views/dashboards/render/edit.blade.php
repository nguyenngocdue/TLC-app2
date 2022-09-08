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
                    <div class="grid-rows-1">
                        @method('PUT')
                        @foreach($props as $key => $value)
                        {{-- {{dd($value['col_span'])}} --}}
                        @php
                        $label = $value['label'];
                        $col_span = $value['col_span'];
                        $column_name = $value['column_name'];
                        $value_column_name = $values->{$column_name};
                        $col_span=$value['col_span'];
                        @endphp
                        {{-- <div class="flex flex-nowrap">
                            <div class="w-1/2 flex">
                                <label class="block text-gray-500 font-bold " for="inline-full-name" title="{{$column_name}}">{{$label}}</label>
                                <input name="{{$column_name}}" class="bg-gray-200" type="text" value="{{$value_column_name}}">
                            </div>
                        </div> --}}
                            <div class="columns-1">
                                <div class="grid-rows-1 flex items-center">
                                    <div class="columns-2">
                                        <label 
                                            class="text-right block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" 
                                            for="grid-last-name">{{$col_span === "12" ? $label : null}}
                                        </label>
                                    </div>
                                        <input 
                                            class="w-full" 
                                            id="grid-last-name" 
                                            type="text"
                                            name="{{$col_span === "12" ? $column_name : null}}" 
                                            value="{{$col_span === "12" ? $value_column_name : null}}"
                                        >
                                </div>
                                
                                {{-- <div class="columns-2">
                                    <div class="grid-rows-1 flex items-center">
                                        <div class="columns-1">
                                            <label class="text-right block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">Email</label>
                                        </div>
                                        <input class="w-full" id="grid-last-name" type="text" placeholder="123">
                                    </div>
                                    <div class="grid-rows-1 flex items-center">
                                        <div class="columns-1">
                                            <label class="text-right block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">Name</label>
                                        </div>
                                        <input class="w-full" id="grid-last-name" type="text" placeholder="123">
                                    </div>
                                </div> --}}
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
