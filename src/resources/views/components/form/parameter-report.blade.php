<form action="{{$route}}" method="post" class="w-full no-print flex flex-row-reverse   rounded-lg border bg-gray-100 dark:bg-gray-800 border-gray-300 dark:border-gray-600 mb-5 p-3 ">
    @method('PUT')
    @csrf
    <div class="grid grid-rows-1 ">
        <div class="flex">
            <input type="hidden" name='_entity' value="{{ $entity }}">
            <input type="hidden" name='action' value="updateReport{{$typeReport}}">
            <input type="hidden" name='type_report' value="{{$typeReport}}">
            @foreach($dataSource as $name =>$value)
            @php
            $show = in_array($name, $hiddenItems) ? "hidden" : "";
            @endphp
            @if($name !== "No")
            <div class=" w-48 px-2 {{$show}}">
                <x-renderer.dropdown name="{{$name}}" :dataSource="$value" :itemsSelected="$itemsSelected" :hiddenItems="$hiddenItems" />
            </div>
            @endif
            @endforeach
            <div class="  flex items-end">
                <button type="submit" class="px-4  py-3  inline-block font-medium text-sm leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none active:bg-purple-800 active:shadow-lg">
                    Apply
                </button>
            </div>
        </div>
    </div>

</form>
