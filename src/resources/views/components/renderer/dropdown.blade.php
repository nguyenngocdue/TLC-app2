<select name='{{$name}}' id="{{$name}}" class="w-full form-select  bg-white border border-gray-300  text-sm rounded-lg block   focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
    <option class="py-10" value="" selected>Select your option...</option>
    @foreach($dataSource as $key => $value)
    <option value="{{$key}}" {{$key*1 === $itemsSelected[$name]*1 ? "selected"  :""}} title="ID : {{$key}}">{{$value}}</option>
    @endforeach
</select>

<script type="text/javascript">
    $('#{{$name}}').select2({
        placeholder: ''
        , allowClear: true
    });

</script>
