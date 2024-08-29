<form  action="{{$route}}" method="POST" id="perPageReport">
    @csrf
    <div class="flex items-center lg:justify-end no-print">
        <div class="w-28">
            <input type="hidden" name='action' value="updateReport2">
            <input type="hidden" name='entity_type' value="{{$entityType}}">
            <input type="hidden" name='entity_type2' value="{{$reportType2}}">
            <input type="hidden" name='report_id' value="{{$reportId}}">
            <select onchange="submitForm()"  name="per_page" class="block w-full text-gray-700 rounded-md border border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 sm:text-sm focus:border-1 focus:border-blue-600 dark:focus:border-blue-600 focus:outline-none px-2 py-2">
                @foreach([10,20,30,40,50,100] as $perPage)
                    <option class="text-sm" value="{{$perPage}}" @selected($pageLimit==$perPage)>{{$perPage}} / page</option>
                @endforeach
            </select>
        </div>
    </div>
</form>


<script type="text/javascript">
    function submitForm() {
        var form = document.getElementById('perPageReport');
        form.submit();
    }
</script>