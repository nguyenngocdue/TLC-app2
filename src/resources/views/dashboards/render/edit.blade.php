<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <form class="w-full max-w-sm" method='POST' action="{{route('user_edit.update',$values->id)}}">
        @csrf
        @method('PUT')

        @foreach($props as $key => $value)
        @php
        $label = $value['label'];
        $column_name = $value['column_name'];
        $value = $values->{$column_name};
        @endphp

        <div class="md:flex md:items-center mb-6">
            <div class="md:w-1/3">
                <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name" title="{{$column_name}}">

                    {{$label}}
                </label>
            </div>
            <div class="md:w-2/3">
                <input name="{{$column_name}}" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-full-name" type="text" value="{{$value}}">
            </div>
        </div>
        @endforeach

        <div class="md:flex md:items-center">
            <div class="md:w-1/3"></div>
            <div class="md:w-2/3">
                <button class="shadow bg-purple-500 hover:bg-purple-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                    Update
                </button>
            </div>
        </div>
    </form>
</body>
</html>
