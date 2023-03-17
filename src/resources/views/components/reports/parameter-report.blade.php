@php
$modeParams = ['mode_001'];
$route = $routeName ? route($routeName) : "";
@endphp

{{-- @dd($routeName) --}}
<div class="">
    <div class="flex justify-end ">
        <x-reports.reset-param-report typeReport="{{$typeReport}}" entity="{{$entity}}" route="{{ route('updateUserSettings') }}" modeName='{{$modeOption}}' />
    </div>
    <form action="{{$route}}" method="GET" class="flex flex-row-reverse">
        <div class="grid grid-rows-1 ">
            <div class="flex">
                <input type="hidden" name='_entity' value="{{ $entity }}">
                <input type="hidden" name='action' value="updateReport{{$typeReport}}">
                <input type="hidden" name='type_report' value="{{$typeReport}}">
                <input type="hidden" name='mode_option' value="{{$modeOption}}">
                <input type="hidden" name='form_type' value="updateParams">
                @foreach($columns as $key =>$value)
                @php
                $title = isset($value['title']) ? $value['title'] : ucwords(str_replace('_', " ", $value['dataIndex']));
                $name = $value['dataIndex'];
                $data = $dataSource[$name];
                $allowClear = $value['allowClear'] ?? false;
                @endphp
                <div class=" w-72 px-2 ">
                    <x-reports.dropdown6 title="{{$title}}" name="{{$name}}" allowClear={{$allowClear}} :dataSource="$data" :itemsSelected="$itemsSelected" />
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
