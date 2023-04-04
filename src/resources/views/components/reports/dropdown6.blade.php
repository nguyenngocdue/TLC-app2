<div class="w-full flex flex-col">
    <div class="text-left whitespace-nowrap">
        <span class="px-1 ">{{$title}}</span>
    </div>
    {{-- @dump($formName) --}}
    <select onchange="{{$formName}}.submit()" name='{{$name}}' id="{{$name}}" class="w-full form-select  bg-white border border-gray-300  text-sm rounded-lg block focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        <option class="py-10 w-full " value="" selected>Select your option...</option>
        @foreach($dataSource as $key => $value)
        @php
        $value = ucwords(str_replace('_', " ", $value));
        $selected = "";
        if (isset($itemsSelected[$name])) {
        if (is_numeric($key) && is_numeric($itemsSelected[$name])) {
        $selected = $key*1 === $itemsSelected[$name]*1 ? "selected" :"";
        } else{
        $selected = $key === $itemsSelected[$name] ? "selected" :"";
        }
        }
        @endphp
        <option class="py-10 w-full " value="{{$key}}" {{$selected}} title="ID : {{$key}}">{{$value}}</option>
        @endforeach
    </select>
</div>

<script type="text/javascript">
    $('#{{$name}}').select2({
        placeholder: ''
        , allowClear: '{{$allowClear}}'
    });

</script>
