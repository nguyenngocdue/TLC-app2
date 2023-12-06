{{-- @dump($dynamicAnswer) --}}
{{-- @dump($staticAnswer) --}}
<div class="grid grid-cols-10">
    @foreach($dynamicAnswer as $id => $label)
    <div class="option text-center col-span-1 m-1 p-4 rounded hover:bg-gray-400" onclick="">
        <input class="cursor-pointer" type="radio" id="option_{{$questionId}}_{{$id}}" name="question_{{$questionId}}" value="{{$id."|".$label}}">
        <br/>
        <label class="cursor-pointer" for="option_{{$questionId}}_{{$id}}">{{$label}}</label><br>
    </div>
    @endforeach
</div>
