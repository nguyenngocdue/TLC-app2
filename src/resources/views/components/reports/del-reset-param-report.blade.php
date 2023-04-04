<form action="{{$route}}" method="post" class="m-0">
    @method('PUT')
    @csrf
    <input type="hidden" name='_entity' value="{{ $entity }}">
    <input type="hidden" name='action' value="updateReport{{Str::ucfirst($typeReport)}}">
    <input type="hidden" name='type_report' value="{{$typeReport}}">
    <input type="hidden" name='mode_option' value="{{$modeOption}}">
    <input type="hidden" name='form_type' value="resetParamsReport">
    <x-renderer.button htmlType="submit" type="secondary"><i class="fa-sharp fa-solid fa-circle-xmark"></i> Reset</x-renderer.button>
</form>
