{{-- @dump($dynamicAnswerRows) --}}
{{-- @dump($staticAnswer) --}}

@php
    $value = ($line) ? $line[0][0]->response_ids : "";
    $values = $value ? explode("|||", $value) : [];
    // dump($values);
@endphp

<x-question-answer.question-answer-badge id="{{$questionJSKey}}" selected="{{$value}}" validation="{{$validation}}"/>
@foreach($dynamicAnswerRowGroups as $groupName)
    @if($groupName != 'no_group')    
        <b>{{$groupName}}</b>
    @endif
    <div class="grid {{$renderAsRows ?: 'grid-cols-12'}}">
        @foreach($dynamicAnswerRows[$groupName] as $id => $object)
            @php 
                $label = $object['name'];
                $id = $object['id'];
                $avatar = $object['avatar'] ?? null;
            @endphp
            <div class="{{$renderAsRows ? 'flex items-center' : 'text-center'}} col-span-1 m-1 p-2 rounded hover:bg-blue-100" onclick="">
                <input class="cursor-pointer" type="checkbox" 
                    id="option_{{$questionId}}_{{$id}}" 
                    name="question_{{$questionId}}[]" 
                    @checked(in_array($id,$values))
                    value="{{$id}}:::{{$label}}">
                @if($renderAsRows)
                    <label class="cursor-pointer flex items-center px-2" for="option_{{$questionId}}_{{$id}}"> 
                        @if($avatar) <img class="rounded-full w-8 h-8 m-2" src="{{$avatar}}" /> @endif
                        {{$label}}
                    </label>
                @else
                    <br/>
                    <label class="cursor-pointer" for="option_{{$questionId}}_{{$id}}"> 
                        <div class="flex {{$renderAsRows ?:"justify-center"}}">
                            <img class="rounded-full w-8 h-8 m-2" src="{{$avatar}}" />
                        </div>{{$label}}
                    </label>
                @endif
                {{-- <br> --}}
            </div>
        @endforeach
    </div>
@endforeach