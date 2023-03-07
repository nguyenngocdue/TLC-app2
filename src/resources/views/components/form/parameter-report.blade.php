@php
$modeParams = ['mode_001'];
@endphp

<div class="w-full no-print    rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3">
    <div class="flex justify-end ">
        <x-form.reset-param-report typeReport="{{$typeReport}}" entity="{{$entity}}" route="{{ route('updateUserSettings') }}" :modeNames='$modeParams' />
    </div>
    <form action="{{$route}}" method="post" class="flex flex-row-reverse">
        @method('PUT')
        @csrf
        <div class="grid grid-rows-1 ">
            <div class="flex">
                <input type="hidden" name='_entity' value="{{ $entity }}">
                <input type="hidden" name='action' value="updateReport{{$typeReport}}">
                <input type="hidden" name='type_report' value="{{$typeReport}}">
                @foreach($columns as $key =>$value)
                @php
                $title = isset($value['title']) ? $value['title'] : ucwords(str_replace('_', " ", $value['dataIndex']));
                $name = $value['dataIndex'];
                $data = $dataSource[$name];
                @endphp
                <div class=" w-72 px-2 ">
                    <x-renderer.dropdown title="{{$title}}" name="{{$name}}" :dataSource="$data" :itemsSelected="$itemsSelected" />
                </div>
                @endforeach
                <div class="  flex items-end">
                    <div class=" ">
                        <button type="submit" class="px-4  py-3  inline-block font-medium text-sm leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none active:bg-purple-800 active:shadow-lg">
                            Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
