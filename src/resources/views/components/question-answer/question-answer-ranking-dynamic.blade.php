{{-- @dump($questionId, $staticAnswer, $dynamicAnswerRows) --}}
@php
    // $value = ($line) ? $line[0][0]->response_ids : "";
    // dump($line);
@endphp

@php $countAllDynamicAnswer = $dynamicAnswerRows->map(fn($i)=>count($i))->sum(); @endphp

<div class="w-full rounded bg-red-600 p-2 text-white font-bold">
    <i>1 is the HIGHEST, {{$countAllDynamicAnswer}} is the LOWEST.</i>
</div>
<table class="border rounded m-4"> 
    <tr>
        <th class='border bg-gray-300 min-w-[100px]'></th>
        @for($i = 1; $i <= $countAllDynamicAnswer; $i++)
            <th class='border bg-gray-300 px-2'>{{$i}}</th>
        @endfor
    </tr>
    @php $index=0; @endphp
    @foreach($dynamicAnswerRowGroups as $groupName)
        @if($groupName != 'no_group')  
            <tr><th class="bg-gray-200 text-left px-2" colspan="{{$countAllDynamicAnswer + 1}}">{{$groupName}}</th></tr>
        @endif
        @foreach($dynamicAnswerRows[$groupName] as $id=>$object)
            @php 
                $label = $object['name'];
                $id = $object['id'];
                $avatar = $object['avatar'] ?? null;
            @endphp
            <tr class="hover:bg-blue-100">
                    <td class="border px-2 py-1 flex items-center">
                        @php
                           $value = ($line[$id][0]->response_values ?? "");
                           $questionJSKey = "{$questionId}_{$id}";
                        @endphp
                        {{-- <script>questions['{{$questionJSKey}}']='{{$value}}';</script> --}}
                        <x-question-answer.question-answer-badge id="{{$questionJSKey}}" selected="{{$value}}" validation="{{$validation}}"/>
                        @if($avatar) <img class="rounded-full w-8 h-8 m-2" src="{{$avatar}}" /> @endif
                        {{$label}}
                    </td>
                @for($i = 1; $i <= $countAllDynamicAnswer; $i++)
                    @php
                        $i_1 = $i-1;
                        $chkId = "chk_{$questionId}_{$index}_{$i_1}";
                        $name = "question_{$questionId}_{$id}";
                        if($subQuestion2Id) $name .= "_$subQuestion2Id";
                        $values = explode("|||", $value);
                        // dump($values);
                        $checked = in_array($i, $values);
                    @endphp
                    <td class="border px-2 py-1 text-center">
                        {{-- {{$chkId}} --}}
                        <input 
                            name="{{$name}}" 
                            id="{{$chkId}}" 
                            value="{{$i}}:::{{$i}}"  
                            type="checkbox"
                            @checked($checked)
                            @disabled($readOnly)
                            class="disabled:bg-gray-500 disabled:cursor-not-allowed"
                            onclick="reRenderRankTable({{$questionId}}, {{$countAllDynamicAnswer}})"
                            onchange="refreshValidation('{{$questionJSKey}}', '{{$validation}}', countCheckedByName('{{$name}}'))" 
                            />
                    </td>
                @endfor
            </tr>
            @php $index++; @endphp
        @endforeach
    @endforeach
    <tr>
        <th class='border bg-gray-300 min-w-[100px]'></th>
        @for($i = 1; $i <= $countAllDynamicAnswer; $i++)
            <th class='border bg-gray-300 px-2'>{{$i}}</th>
        @endfor
    </tr>
</table>

<script>
    reRenderRankTable({{$questionId}}, {{$countAllDynamicAnswer}}, {{$readOnly}})
</script>
