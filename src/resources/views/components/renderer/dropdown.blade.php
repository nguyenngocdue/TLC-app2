<div class="grid grid-row-1">
    <div class="grid grid-cols-12 items-center">
        <div class="col-start-1 col-span-3 w-full text-right whitespace-nowrap">
            <span class="px-1 ">{{ucwords(str_replace('_', " ", $name ))}}:</span>
        </div>
        <div class="col-start-4 col-span-9 w-full">
            <select name='{{$name}}' id="{{$name}}" class="w-full form-select  bg-white border border-gray-300  text-sm rounded-lg block   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                <option class="py-10 w-full " value="" selected>Select your option...</option>
                @foreach($dataSource as $key => $value)
                @php
                $selected = isset($itemsSelected[$name]) && $key*1 === $itemsSelected[$name]*1 ? "selected" :""
                @endphp
                <option class="py-10 w-full " value="{{$key}}" {{$selected}} title="ID : {{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>


<script type="text/javascript">
    $('#{{$name}}').select2({
        placeholder: ''
        , allowClear: true
    });

</script>
