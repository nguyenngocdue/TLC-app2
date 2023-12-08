{{-- @dump($questionId, $staticAnswer, $dynamicAnswerRows) --}}
<table class="border rounded m-4"> 
    <tr>
        <th class='border bg-gray-300'></th>
        @foreach($staticAnswer as $value)
            <th class='border bg-gray-300 px-2'>{{$value}}</th>
        @endforeach
    </tr>
    @php $index=0; @endphp
    @foreach($dynamicAnswerRows as $id=>$name)
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
                        type="radio"
                        class="disabled:bg-gray-500 disabled:cursor-not-allowed"
                        />
                </td>
            @endfor
        </tr>
        @php $index++; @endphp
    @endforeach
    <tr>
        <th class='border bg-gray-300'></th>
        @foreach($staticAnswer as $value)
            <th class='border bg-gray-300 px-2'>{{$value}}</th>
        @endforeach
    </tr>
</table>