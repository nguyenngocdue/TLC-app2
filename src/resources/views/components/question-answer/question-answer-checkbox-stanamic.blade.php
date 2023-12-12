{{-- @dump($questionId, $staticAnswer, $dynamicAnswerRows) --}}
{{-- @dump($staticAnswer) --}}
<table class="border rounded m-4"> 
    <tr>
        <th class='border bg-gray-300 min-w-[100px]'></th>
        @foreach($staticAnswer as $value)
            <th class='border bg-gray-300 px-2'>{{$value}}</th>
        @endforeach
    </tr>
    @php $index=0; @endphp
    @foreach($dynamicAnswerRowGroups as $groupName)
        @if($groupName != 'no_group')
            <tr><th class="bg-gray-200 text-left px-2" colspan="{{sizeof($staticAnswer) + 1}}">{{$groupName}}</th></tr>
        @endif
        @foreach($dynamicAnswerRows[$groupName] as $id=>$object)
                @php 
                    $label = $object['name'];
                    $id = $object['id'];
                    $avatar = $object['avatar'] ?? null;
                @endphp
                <tr class="hover:bg-blue-100">
                    <td class="border px-5 py-1 flex items-center">
                        @php
                            if($subQuestion2Id) {
                                $value = ($line[$id][$subQuestion2Id]->response_values ?? '');
                                $questionJSKey = "{$questionId}_{$id}_{$subQuestion2Id}";
                            } else {
                                $value = ($line[$id][0]->response_values ?? '');
                                $questionJSKey = "{$questionId}_{$id}";
                            }
                        @endphp
                        {{-- <script>questions['{{$questionJSKey}}']='{{$value}}';</script> --}}
                        <x-question-answer.question-answer-badge id="{{$questionJSKey}}" selected="{{$value}}" validation="{{$validation}}"/>
                        @if($avatar) <img class="rounded-full w-8 h-8 m-2" src="{{$avatar}}" /> @endif
                        {{$label}}
                    </td>
                    @for($i = 1; $i <= sizeof($staticAnswer); $i++)
                        @php
                            $i_1 = $i-1;
                            $chkId = "chk_{$questionId}_{$index}_{$i_1}";
                            $name = "question_{$questionId}_{$id}";
                            if($subQuestion2Id) {
                                $name .= "_$subQuestion2Id";
                            }
                            $values = $value ? explode("|||", $value) : []; 
                            $checked = in_array($staticAnswer[$i_1], $values);
                        @endphp
                        <td class="border px-5 py-1 text-center">
                            {{-- {{$chkId}} --}}
                            <input 
                                name="{{$name}}[]"
                                id="{{$chkId}}" 
                                value="{{$i}}:::{{$staticAnswer[$i_1]}}"  
                                @checked($checked)
                                type="checkbox"
                                onchange="refreshValidation('{{$questionJSKey}}', '{{$validation}}', countCheckedByName('{{$name}}[]'))" 
                                class="disabled:bg-gray-500 disabled:cursor-not-allowed"
                                />

                            <script>
                                // This will overide refreshValidation in the badge as that one doesnt know how to count
                                refreshValidation('{{$questionJSKey}}', '{{$validation}}', countCheckedByName('{{$name}}[]'))
                            </script>
                        </td>
                    @endfor
                </tr>
                @php $index++; @endphp
        @endforeach
    @endforeach
    <tr>
        <th class='border bg-gray-300 min-w-[100px]'></th>
        @foreach($staticAnswer as $value)
            <th class='border bg-gray-300 px-2'>{{$value}}</th>
        @endforeach
    </tr>
</table>