<div class="flex justify-between no-print bg-gray-50 dark:bg-gray-800 text-purple-600 dark:text-purple-300 mb-2">
    <div class="flex-1"></div>
    <form action="{{route('updateUserSettings')}}" class="w-36 p-1" method="post">
        @method('put')
        @csrf
        <input type="hidden" name="_entity" value="{{$type}}">
        <input type="hidden" name="action" value="updateOptionPrintLayout">
        <select 
        name="option_print_layout" 
        class="{{$class}}" onchange="this.form.submit()">
        <option value="portrait" @selected($value == 'portrait')>Portrait</option>
        <option value="landscape" @selected($value == 'landscape')>Landscape</option>
        </select>
    </form>
    
</div>