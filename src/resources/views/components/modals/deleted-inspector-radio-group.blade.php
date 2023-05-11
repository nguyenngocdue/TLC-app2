@php
    $classRadioGroup = App\Utils\ClassList::RADIO_GROUP;
@endphp
<div class="{{$classRadioGroup}}">
    @foreach($inspectors as $key => $value)
    <label for="{{$key}}" class="flex align-middle items-center">
        <input id="{{$key}}" type="radio" name="inspector_id" value="{{$value->id}}">
        <x-renderer.avatar-user>{!!$value!!}</x-renderer.avatar-user>
    </label>
        
    @endforeach
</div>