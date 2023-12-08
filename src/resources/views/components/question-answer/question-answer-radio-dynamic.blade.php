{{-- @dump($dynamicAnswerRows) --}}
{{-- @dump($staticAnswer) --}}
@foreach($dynamicAnswerRowGroups as $groupName)
    @if($groupName != 'no_group')    
    <b>{{$groupName}}</b>
    @endif
    <div class="grid {{$renderAsRows ?: 'grid-cols-12'}}">
        @foreach($dynamicAnswerRows[$groupName] as $id => $object)
            @php
                $label = $object['name'];
                $id = $object['id'];
            @endphp
            <div class="{{$renderAsRows ?: 'text-center'}} col-span-1 m-1 p-2 rounded hover:bg-blue-100" onclick="">
                <input class="cursor-pointer" type="radio" id="option_{{$questionId}}_{{$id}}" name="question_{{$questionId}}" value="{{$id."|".$label}}">
                @if(!$renderAsRows) <br/> @endif
                <label class="cursor-pointer" for="option_{{$questionId}}_{{$id}}">{{$label}}</label><br>
            </div>
        @endforeach
    </div>
@endforeach