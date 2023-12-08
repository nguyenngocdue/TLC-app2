{{-- @dump($questionId, $staticAnswer, $staticAnswer) --}}
<table class="border rounded m-4"> 
    <tr>
        <th class='border bg-gray-300'></th>
        @for($i = 1; $i <= sizeof($staticAnswer); $i++)
            <th class='border bg-gray-300 px-2'>{{$i}}</th>
        @endfor
    </tr>
    @php $index=0; @endphp
    @foreach($staticAnswer as $id=>$name)
        <tr class="hover:bg-blue-100">
            <td class="border px-5 py-1">{{$name}}</td>
            @for($i = 1; $i <= sizeof($staticAnswer); $i++)
                @php
                    $i_1 = $i-1;
                    $chkId = "chk_{$questionId}_{$index}_{$i_1}";
                @endphp
                <td class="border px-5 py-1 text-center">
                    {{-- {{$chkId}} --}}
                    <input 
                        name="question_{{$questionId}}[]" 
                        id="{{$chkId}}" 
                        value="{{$name}}:{{$i}}"  
                        type="checkbox"
                        class="disabled:bg-gray-500 disabled:cursor-not-allowed"
                        onclick="onRankClick('{{$chkId}}', {{$questionId}}, {{sizeof($staticAnswer)}})"
                        />
                </td>
            @endfor
        </tr>
        @php $index++; @endphp
    @endforeach
    <tr>
        <th class='border bg-gray-300'></th>
        @for($i = 1; $i <= sizeof($staticAnswer); $i++)
            <th class='border bg-gray-300 px-2'>{{$i}}</th>
        @endfor
    </tr>
</table>

<i>1 is the highest, {{sizeof($staticAnswer)}} is the lowest.</i>



