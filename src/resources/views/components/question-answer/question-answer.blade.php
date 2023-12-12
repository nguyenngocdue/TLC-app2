@php
    $questionId = $item['id'];
    dump($line);
@endphp
<div class="sticky top-[114px] bg-blue-400 rounded mb-2" >
    <x-renderer.heading level=5 class="z-10 p-2">
        <p title="#{{$questionId}}">{{$item['name'] ?? 'Untitled'}}</p>
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
            
            $inputType = $debug ? 'text' : 'hidden';

            foreach($loop as $subQuestions){
                foreach($subQuestions as $subQuestion){
                    // echo $subQuestion;
                    if($showSubQuestion){ 
                        $avatar = $subQuestion['avatar'] ?? null;
                        echo "<div class='flex items-center' title='".$subQuestion['id']."'>";
                            // echo $avatar;
                        if($avatar) echo '<img class="rounded-full w-8 h-8 m-2" src="'.$avatar.'" /> ';
                        echo "<b>". $subQuestion['name']."</b>" ?? '';
                        echo "</div>";
                        // dump($subQuestion);
                        $name = "description_{$questionId}_{$subQuestion['id']}_2";
                        if($debug) echo $name;
                        echo "<input type='$inputType' name='$name' value='{$subQuestion['name']}'/>";
                        if($debug) echo "<br/>";
                    }

                    foreach($dynamicAnswerRows as $subQuestionGroups){
                        foreach($subQuestionGroups as $subQuestion1){
                            $name ="description_{$questionId}_{$subQuestion1['id']}_1";
                            if($debug) echo $name;
                            echo "<input type='$inputType' name='$name' value='{$subQuestion1['name']}' />";
                        }
                    }
                    
                    if($debug) echo "<br/>";
                    echo "<input type='$inputType' name='control_{$questionId}' value='{$controlId}' />";

                    echo Blade::render('<x-question-answer.question-answer-'.$control.'
                    :questionId="$questionId" 
                    :staticAnswer="$staticAnswer" 
                    :dynamicAnswerRows="$dynamicAnswerRows" 
                    :dynamicAnswerRowGroups="$dynamicAnswerRowGroups" 
                    
                    :renderAsRows="$renderAsRows"
                    :subQuestion2Id="$subQuestion2Id"
                    />', [
                        'questionId' => $questionId,
                        'staticAnswer' => $staticAnswer,
                        'dynamicAnswerRows' => $dynamicAnswerRows,
                        'dynamicAnswerRowGroups' => $dynamicAnswerRowGroups,
                        'renderAsRows' => $renderAsRows,
                        'subQuestion2Id' => $subQuestion['id'] ?? null,
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