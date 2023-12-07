{{-- @dump($staticAnswer) --}}
{{-- @dump($questionId) --}}
<div class="grid grid-cols-10">
    @foreach($staticAnswer as $label)
    <div class="option text-center col-span-1 m-1 p-4 rounded hover:bg-blue-100" onclick="">
        <input class="cursor-pointer" type="checkbox" id="option_{{$questionId}}_{{$label}}" name="question_{{$questionId}}[]" value="{{$label}}">
        <br/>
        <label class="cursor-pointer" for="option_{{$questionId}}_{{$label}}">{{$label}}</label><br>
    </div>
    @endforeach
</div>
