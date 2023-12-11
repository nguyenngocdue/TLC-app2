{{-- @dump($questionId, $staticAnswer, $dynamicAnswerRows) --}}
@php $countAllDynamicAnswer = $dynamicAnswerRows->map(fn($i)=>count($i))->sum(); @endphp
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
            @endphp
            <tr class="hover:bg-blue-100">
                    <td class="border px-5 py-1">{{$label}}</td>
                @for($i = 1; $i <= $countAllDynamicAnswer; $i++)
                    @php
                        $i_1 = $i-1;
                        $chkId = "chk_{$questionId}_{$index}_{$i_1}";
                    @endphp
                    <td class="border px-5 py-1 text-center">
                        {{-- {{$chkId}} --}}
                        <input 
                            name="question_{{$questionId}}[]" 
                            id="{{$chkId}}" 
                            value="{{$label}}:{{$i}}"  
                            type="checkbox"
                            class="disabled:bg-gray-500 disabled:cursor-not-allowed"
                            onclick="onRankClick('{{$chkId}}', {{$questionId}}, {{$countAllDynamicAnswer}})"
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

<i>1 is the highest, {{$countAllDynamicAnswer}} is the lowest.</i>