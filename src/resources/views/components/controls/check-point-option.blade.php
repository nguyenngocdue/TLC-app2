<div class="my-4">
    @foreach($controlGroupNames as $id => $name)
    <label for="radio_{{$line->id}}_{{$id}}" class="border bg-gray-400 p-4 rounded mx-0.5">
        <input name="radio_{{$line->id}}" id="radio_{{$line->id}}_{{$id}}" type="radio" value="{{$id}}"> {{$name}}
    </label>
    @endforeach
</div>