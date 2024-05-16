{{-- <form action="{{$route}}" method="post">
    @method('PUT')
    @csrf
    <div class="flex items-center lg:justify-end">
        <div class="w-28">
            <input type="hidden" name='_entity' value="{{ $type }}">
            <input type="hidden" name='action' value="updatePerPage">
            <select name="per_page" class="{{$classList}}" onchange="this.form.submit()">
                @foreach([10,15,20,30,40,50,100] as $value)
                <option class="text-sm" value="{{$value}}" @selected($perPage==$value)>{{$value}} / page</option>
                @endforeach
            </select>
        </div>

    </div>
</form> --}}
<div class="flex items-center lg:justify-end">
    <div class="w-28">
        <select id="select_per_page" name="per_page" class="{{$classList}}" onchange="submitPerPage('{{$route}}',this.value, '{{$key}}')">
            @foreach([10,15,20,30,40,50,100] as $value)
            <option class="text-sm" value="{{$value}}" @selected($perPage==$value)>{{$value}} / page</option>
            @endforeach
        </select>
    </div>
</div>
<script>
    var entityPerPage = @json($type);
    function submitPerPage(url,perPage,key){
        if(perPage){
            const data = {
            entity: entityPerPage,
            per_page:perPage,
            key,
            }
            $.ajax({
            type: 'put',
            url,
            data: data,
            success: function (response) {
                if(response.success){
                    toastr.success(response.message);
                    window.location.reload();
                }
            },
            error: function (jqXHR) {
                toastr.error(jqXHR.responseJSON.message)
                },
            })
        }
    }
</script>

