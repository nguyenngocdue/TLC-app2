{{-- @dump($dynamicAnswer) --}}
{{-- @dump($staticAnswer) --}}
<div class="grid {{$renderAsRows ?: 'grid-cols-10'}}">
    @foreach($dynamicAnswer as $id => $label)
    <div class="option text-center1 col-span-1 m-1 p-2 rounded hover:bg-blue-100" onclick="">
        <input class="cursor-pointer" type="radio" id="option_{{$questionId}}_{{$id}}" name="question_{{$questionId}}" value="{{$id."|".$label}}">
        @if(!$renderAsRows) <br/> @endif
        <label class="cursor-pointer" for="option_{{$questionId}}_{{$id}}">{{$label}}</label><br>
    </div>
    @endforeach
</div>
