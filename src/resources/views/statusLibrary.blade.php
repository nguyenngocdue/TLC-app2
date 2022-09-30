<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <script href="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>


</head>
<body>


    <div class="grid grid-rows-1 bg-gray-300">
        {{-- {{dd($libStatus)}} --}}
        <div class="mx-auto flex min-h-screen items-center py-5">
            <form class="rounded-md bg-white p-4" id="form-upload" method="POST" action="{{route('manage_statusLibrary.update', 1) }}">
                @csrf
                @method('PUT')
                <table id="table_manage" class="whitespace-no-wrap w-full table-auto">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            <th class="px-4 py-3"></th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Color</th>
                            <th class="px-4 py-3">Renderd</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-white dark:divide-gray-700 dark:bg-gray-800">
                        @foreach($libStatus as $key => $props)
                        <tr class=" text-gray-700 dark:text-gray-400 bg-orange-500">
                            <td class="px-4 py-3 text-sm">
                                {{$key}}
                            </td>
                        </tr>


                        @foreach($props as $key => $prop)
                        <tr class=" text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                <input hidden>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <input name="oldName[]" class="col-span-3" value={{$key}}>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <input name="oldTitle[]" class="col-span-3" id="inline-full-name" type="text" value="{{$prop['title']}}">
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <input name="oldColor[]" class="col-span-3 bg-[{{$prop['color']}}]" id="inline-full-name" type="text" value="{{$prop['color']}}">
                            </td>
                            <td class="px-4 py-3 text-sm text-white">
                                <span class="col-span-3 bg-[{{$prop['color']}}]">{{$prop['title']}}</span>
                            </td>
                        </tr>
                        @endforeach

                        @endforeach


                        <td class="px-4 py-3 text-sm">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="createStatus">
                                Create
                            </label>
                        </td>
                        <tr class=" text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-sm">
                                <input name="name[]" class="col-span-3 border rounded" id="createStatus" type="text" placeholder="Enter name">
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <input name="title" class="col-span-3 border rounded" id="inline-full-name" type="text" placeholder="Enter title">
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <input name="color" class="col-span-3 border rounded" id="inline-full-name" type="text" placeholder="Enter color">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" class="focus:shadow-outline rounded bg-purple-500 py-2 px-4 font-bold text-white shadow hover:bg-purple-400 focus:outline-none" type="button">
                    Update
                </button>
        </div>
        </form>
    </div>
    </div>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    {!! Toastr::message() !!}



    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-blue-100 dark:text-blue-100">
            <thead class="text-xs text-white uppercase bg-blue-600 border-b border-blue-400 dark:text-white">
                <tr>
                    <th scope="col" class="py-3 px-6">
                        Product name
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Color
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Category
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Price
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-blue-600 border-b border-blue-400 hover:bg-blue-500">
                    <th scope="row" class="py-4 px-6 font-medium text-blue-50 whitespace-nowrap dark:text-blue-100">
                        Apple MacBook Pro 17"
                    </th>
                    <td class="py-4 px-6">
                        Sliver
                    </td>
                    <td class="py-4 px-6">
                        Laptop
                    </td>
                    <td class="py-4 px-6">
                        $2999
                    </td>
                    <td class="py-4 px-6">
                        <a href="#" class="font-medium text-white hover:underline">Edit</a>
                    </td>
                </tr>
                <tr class="bg-blue-600 border-b border-blue-400 hover:bg-blue-500">
                    <th scope="row" class="py-4 px-6 font-medium text-blue-50 whitespace-nowrap dark:text-blue-100">
                        Microsoft Surface Pro
                    </th>
                    <td class="py-4 px-6">
                        White
                    </td>
                    <td class="py-4 px-6">
                        Laptop PC
                    </td>
                    <td class="py-4 px-6">
                        $1999
                    </td>
                    <td class="py-4 px-6">
                        <a href="#" class="font-medium text-white hover:underline">Edit</a>
                    </td>
                </tr>
                <tr class="bg-blue-600 border-b border-blue-400 hover:bg-blue-500">
                    <th scope="row" class="py-4 px-6 font-medium text-blue-50 whitespace-nowrap dark:text-blue-100">
                        Magic Mouse 2
                    </th>
                    <td class="py-4 px-6">
                        Black
                    </td>
                    <td class="py-4 px-6">
                        Accessories
                    </td>
                    <td class="py-4 px-6">
                        $99
                    </td>
                    <td class="py-4 px-6">
                        <a href="#" class="font-medium text-white hover:underline">Edit</a>
                    </td>
                </tr>
                <tr class="bg-blue-600 border-b border-blue-400 hover:bg-blue-500">
                    <th scope="row" class="py-4 px-6 font-medium text-blue-50 whitespace-nowrap dark:text-blue-100">
                        Google Pixel Phone
                    </th>
                    <td class="py-4 px-6">
                        Gray
                    </td>
                    <td class="py-4 px-6">
                        Phone
                    </td>
                    <td class="py-4 px-6">
                        $799
                    </td>
                    <td class="py-4 px-6">
                        <a href="#" class="font-medium text-white hover:underline">Edit</a>
                    </td>
                </tr>
                <tr class="bg-blue-600 border-blue-400 hover:bg-blue-500">
                    <th scope="row" class="py-4 px-6 font-medium text-blue-50 whitespace-nowrap dark:text-blue-100">
                        Apple Watch 5
                    </th>
                    <td class="py-4 px-6">
                        Red
                    </td>
                    <td class="py-4 px-6">
                        Wearables
                    </td>
                    <td class="py-4 px-6">
                        $999
                    </td>
                    <td class="py-4 px-6">
                        <a href="#" class="font-medium text-white hover:underline">Edit</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
