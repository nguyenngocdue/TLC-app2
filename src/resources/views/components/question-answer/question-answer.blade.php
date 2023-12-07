<x-renderer.card title="{{$item['name'] ?? 'Untitled'}}">
    {{$item['description']}} 
    <hr/>
    @php
        echo Blade::render('<x-question-answer.question-answer-'.$control.'
        :questionId="$questionId" 
        :staticAnswer="$staticAnswer" 
        :dynamicAnswer="$dynamicAnswer" 
        :renderAsRows="$renderAsRows"
        />', [
            'questionId' => $item['id'],
            'staticAnswer' => $staticAnswer,
            'dynamicAnswer' => $dynamicAnswer,
            'renderAsRows' => $renderAsRows,
        ]);
    @endphp
 
</x-renderer.card>
            
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