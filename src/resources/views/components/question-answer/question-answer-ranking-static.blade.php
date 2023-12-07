{{-- @dump($questionId, $staticAnswer, $staticAnswer) --}}
<table class="border rounded m-4"> 
    <tr>
        <th class='border bg-gray-300'>Rank</th>
        @for($i = 1; $i <= sizeof($staticAnswer); $i++)
            <th class='border bg-gray-300'>{{$i}}</th>
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
                <td class="border px-5 py-1">
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
        <th class='border bg-gray-300'>Rank</th>
        @for($i = 1; $i <= sizeof($staticAnswer); $i++)
            <th class='border bg-gray-300'>{{$i}}</th>
        @endfor
    </tr>
</table>

@once
<script>
    const onRankClick = (chkId, questionId, count) => {
        let array = [];
        const checked = []
        for (let i = 0; i < count; i++) {
            array[i] = [];
            for (let j = 0; j < count; j++) {
                array[i][j] = 0;
                const id = "chk_" + questionId + "_" + i + "_"+j;
                if( $("#"+id).prop('checked')){
                    checked.push([i,j])
                }
            }
        }

        for(let k = 0; k < checked.length; k++) {
            const [a,b] = checked[k];
            // console.log(a,b)
            for(let i =0; i< count;i++){
                array[a][i] = 1
                array[i][b] = 1
            }
        }

        for(let k = 0; k < checked.length; k++) {
            const [a,b] = checked[k];
            array[a][b] = 0
        }

        for (let i = 0; i < count; i++) {
            for (let j = 0; j < count; j++) {
                const id = "chk_" + questionId + "_" + i + "_"+j;
                const disabled = array[i][j]
                $("#"+id).prop('disabled', disabled);
            }
        }

        console.log(array)
    }
</script>
@endonce