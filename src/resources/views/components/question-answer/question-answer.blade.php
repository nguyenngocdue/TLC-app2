<div class="sticky top-[114px] bg-blue-400 rounded mb-2">
    <x-renderer.heading level=5 class="z-10 p-2">
        {{$item['name'] ?? 'Untitled'}}
    </x-renderer.heading>
    <div class="p-1 mx-4"> {{$item['description']}}  </div>
</div>
<div class="flex">
    <div class="px-1"></div>
    <div class="bg-blue-400 px-1 rounded"></div>
    <div class="px-1"></div>                        
    <div class="w-full">
        @php
            $showSubQuestion=true;
            switch($control){
                case 'radio-dynanamic':
                    $control = 'radio-stanamic';
                    $loop = $dynamicAnswerCols;
                break;
                case 'checkbox-dynanamic':
                    $control = 'checkbox-stanamic';
                    $loop = $dynamicAnswerCols;
                break;
                default:
                    $loop = [['name' => '']];
                    $showSubQuestion=false;
                break;
            }
            
            foreach($loop as $subQuestions){
                foreach($subQuestions as $subQuestion){
                    // echo $subQuestion;
                    if($showSubQuestion){ 
                        echo $subQuestion['name'] ?? '';
                        // dump($subQuestion);
                    }
                    echo Blade::render('<x-question-answer.question-answer-'.$control.'
                    :questionId="$questionId" 
                    :staticAnswer="$staticAnswer" 
                    :dynamicAnswerRows="$dynamicAnswerRows" 
                    :dynamicAnswerRowGroups="$dynamicAnswerRowGroups" 
                    
                    :renderAsRows="$renderAsRows"
                    />', [
                        'questionId' => $item['id'],
                        'staticAnswer' => $staticAnswer,
                        'dynamicAnswerRows' => $dynamicAnswerRows,
                        'dynamicAnswerRowGroups' => $dynamicAnswerRowGroups,
                        // 'dynamicAnswerCols' => $dynamicAnswerCols,
                        'renderAsRows' => $renderAsRows,
                    ]);
                }
            }
        @endphp
    </div>
</div>
 
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