{{-- @dump($questionId, $staticAnswer, $staticAnswer) --}}
@php
    // dump($line);
    // $value = ($line) ? $line[0][0]->response_ids : "";
    // dump($value);
@endphp
<table class="border rounded m-4"> 
    <tr>
        <th class='border bg-gray-300 min-w-[100px]'></th>
        @for($i = 1; $i <= sizeof($staticAnswer); $i++)
            <th class='border bg-gray-300 px-2'>{{$i}}</th>
        @endfor
    </tr>
    @php $index=0; @endphp
    @foreach($staticAnswer as $id=>$name)
        <tr class="hover:bg-blue-100">
            <td class="border px-5 py-1">
                @php
                    $value = ($line[$name][0]->response_values ?? "");
                    $questionJSKey = "{$questionId}_{$name}";
                @endphp
                <script>questions['{{$questionJSKey}}']='{{$value}}';</script>
                <x-question-answer.question-answer-badge id="{{$questionJSKey}}" selected="{{$value}}" validation="{{$validation}}"/>
                {{$name}}
            </td>
            @for($i = 1; $i <= sizeof($staticAnswer); $i++)
                @php
                    $i_1 = $i-1;
                    $chkId = "chk_{$questionId}_{$index}_{$i_1}";
                    // dump( $checked[0]->response_values);
                    $values = explode("|||", $value);
                    $checked = in_array($i, $values);
                @endphp
                <td class="border px-5 py-1 text-center">
                    {{-- {{$chkId}} --}}
                    <input 
                        name="question_{{$questionId}}_{{$name}}" 
                        id="{{$chkId}}" 
                        value="{{$name}}:::{{$i}}"  
                        type="checkbox"
                        @checked($checked)
                        class="disabled:bg-gray-500 disabled:cursor-not-allowed"
                        onclick="reRenderRankTable({{$questionId}}, {{sizeof($staticAnswer)}})"
                        />
                </td>
            @endfor
        </tr>
        @php $index++; @endphp
    @endforeach
    <tr>
        <th class='border bg-gray-300 min-w-[100px]'></th>
        @for($i = 1; $i <= sizeof($staticAnswer); $i++)
            <th class='border bg-gray-300 px-2'>{{$i}}</th>
        @endfor
    </tr>
</table>

<i>1 is the highest, {{sizeof($staticAnswer)}} is the lowest.</i>

<script>
    reRenderRankTable({{$questionId}}, {{sizeof($staticAnswer)}})
</script>

