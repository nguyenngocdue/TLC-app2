<form action="{{$route}}" method="post">
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
        {{-- <div class="mt-2 dark:text-white">/page  </div> --}}
        {{-- <div>
            <x-renderer.button htmlType="submit" type="primary"><i class="fas fa-arrow-right"></i></x-renderer.button>
        </div> --}}
    </div>
</form>
