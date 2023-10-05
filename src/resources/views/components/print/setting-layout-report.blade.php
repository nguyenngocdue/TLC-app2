<div class="flex justify-between no-print bg-gray-100 dark:bg-gray-800 text-purple-600 dark:text-purple-300 mb-2">
    <div class="flex-1"></div>
    <form action="{{route('updateUserSettings')}}" id="{{$entity}}" class="w-36 p-1" method="post">
    @method('put')
        @csrf
        <input type="hidden" name='_entity' value="{{ $entity }}">
        <input type="hidden" name='action' value="updateReport{{Str::ucfirst($typeReport)}}">
        <input type="hidden" name='type_report' value="{{$typeReport}}">
        <select name="optionPrintLayout" class="{{$class}}" onchange="this.form.submit()">
        <option value="portrait" @selected($value == 'portrait')>Portrait</option>
        <option value="landscape" @selected($value == 'landscape')>Landscape</option>
        <input type="hidden" name='mode_option' value="{{$modeOption}}">
        <input type="hidden" name='form_type' value="updateOptionPrintReport">
        </select>
    </form>
    
</div>