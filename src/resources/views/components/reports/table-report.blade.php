<div class="rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 p-3">
    <img class="w-40" src="https://assets.website-files.com/61e52058abc83b0e8416a425/61f0ce6fe8161c72f61be858_logo-blue.svg" />
    <span class="text-2xl font-normal flex justify-center">TLC Inspection Check Sheet</span>
    <div class="  flex items-center justify-center">
        <div class="w-[50%] ">
            <div class="px-4">
                <span>Project: <span>TLC - General Project</span></span>
            </div>
            <div class="bg-white border rounded-lg overflow-hidden">
                <table class="min-w-max w-full table-auto">
                    <thead>
                        <tr class=" bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-1 px-6 text-left">NO.</th>
                            <th class="py-1 px-6 text-left">Sheet Name</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach(array_values($dataSource) as $key => $value)
                        <tr class=" border-gray-200 hover:bg-gray-100">
                            <td class="py-1 px-6 text-left whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="font-medium">{{$key+1}}</span>
                                </div>
                            </td>
                            <td class="py-1 px-6 text-left">
                                <div class="flex items-center ">
                                    @php
                                    $name = App\Utils\Support\Report::slugName($value)
                                    @endphp
                                    <a href="#{{$name}}">{{$value}}</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
