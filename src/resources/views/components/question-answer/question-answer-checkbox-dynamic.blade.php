{{-- @dump($dynamicAnswerRows) --}}
{{-- @dump($staticAnswer) --}}

@php
    $subIndex = ($subQuestion2Id) ? $subQuestion2Id : 0;
    $value = ($line) ? ($line[$subIndex][0]->response_ids ?? "") : "";
    $values = $value ? explode("|||", $value) : [];
    // dump($values);
    $questionJSKey = $questionId;
    if($subQuestion2Id) {
        $questionJSKey .= "_$subQuestion2Id";
    }
@endphp
{{-- <script>questions['{{$questionJSKey}}']='{{$value}}';</script> --}}

<x-question-answer.question-answer-badge id="{{$questionJSKey}}" selected="{{$value}}" validation="{{$validation}}"/>
@if($subQuestion2Id) 
<div class="ml-10"> 
@endif
@foreach($dynamicAnswerRowGroups as $groupName)
    @if($groupName != 'no_group')    
        <b>{{$groupName}}</b>
    @endif
    <div class="grid {{$renderAsRows ?: "grid-cols-$gridCols"}}">
        @foreach($dynamicAnswerRows[$groupName] as $id => $object)
            @php 
                $label = $object['name'];
                $id = $object['id'];
                $avatar = $object['avatar'] ?? null;

                $name = "question_{$questionId}";
                if($subQuestion2Id) {
                    $name .= "_$subQuestion2Id";
                }
            @endphp
            
            <div class="{{$renderAsRows ? 'flex items-center' : 'text-center'}} col-span-1 m-1 p-2 rounded hover:bg-blue-100" onclick="">
                    <input class="cursor-pointer disabled:bg-gray-500 disabled:cursor-not-allowed" type="checkbox" 
                        id="option_{{$name}}_{{$id}}" 
                        name="{{$name}}[]" 
                        @checked(in_array($id,$values))
                        @disabled($readOnly)
                        onchange="refreshValidation('{{$questionJSKey}}', '{{$validation}}', countCheckedByName('{{$name}}[]'))" 
                        value="{{$id}}:::{{$label}}">
                    @if($renderAsRows)
                        <label class="cursor-pointer flex items-center px-2" for="option_{{$name}}_{{$id}}"> 
                            @if($avatar) <img class="rounded-full w-8 h-8 m-2" src="{{$avatar}}" /> @endif
                            {{$label}}
                        </label>
                    @else
                        <br/>
                        <label class="cursor-pointer" for="option_{{$name}}_{{$id}}"> 
                            <div class="flex {{$renderAsRows ?:"justify-center"}}">
                                @if($avatar)  <img class="rounded-full w-8 h-8 m-2" src="{{$avatar}}" /> @endif
                            </div>{{$label}}
                        </label>
                    @endif
                    <script>
                        // This will overide refreshValidation in the badge as that one doesnt know how to count
                        refreshValidation('{{$questionJSKey}}', '{{$validation}}', countCheckedByName('{{$name}}[]'))
                    </script>
                    {{-- <br> --}}
                </div>
                @endforeach
            </div>
            @endforeach
@if($subQuestion2Id) 
</div>
@endif