@php
    $questionId = $item['id'];
    // dump($line);
@endphp
@once
    <script>const questions = {};</script>
@endonce
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
            $showSubQuestion2=true;
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
                    $showSubQuestion2=false;
                break;
            }
            
            $inputType = $debug ? 'text' : 'hidden';

            foreach($loop as $subQuestions){
                foreach($subQuestions as $subQuestion2){
                    // echo $subQuestion2;
                    if($showSubQuestion2){ 
                        $avatar = $subQuestion2['avatar'] ?? null;
                        echo "<div class='flex items-center' title='".$subQuestion2['id']."'>";
                            // echo $avatar;
                        if($avatar) echo '<img class="rounded-full w-8 h-8 m-2" src="'.$avatar.'" /> ';
                        echo "<b>". $subQuestion2['name']."</b>" ?? '';
                        echo "</div>";
                        // dump($subQuestion2);
                        $name = "description_{$questionId}_{$subQuestion2['id']}_2";
                        if($debug) echo $name;
                        echo "<input type='$inputType' name='$name' value='{$subQuestion2['name']}'/>";
                        if($debug) echo "<br/>";
                        // echo "<p>Question_1_2</p>";
                    }

                    foreach($dynamicAnswerRows as $subQuestionGroups){
                        foreach($subQuestionGroups as $subQuestion1){
                            $name ="description_{$questionId}_{$subQuestion1['id']}_1";
                            if($debug) echo $name;
                            echo "<input type='$inputType' name='$name' value='{$subQuestion1['name']}' />";
                        }
                    }

                    foreach($staticAnswer as $index => $subQuestion1){
                        if($control == 'ranking-static'){
                            $name ="description_{$questionId}_{$subQuestion1}_1";
                            if($debug) echo $name;
                            echo "<input type='$inputType' name='$name' value='{$subQuestion1}' />";
                        }
                    }
                    
                    if(in_array($control,[
                        'text', 'textarea',
                        'radio-static','radio-dynamic',
                        'checkbox-static','checkbox-dynamic',
                    ])){
                        $value = $line[0][0]->response_ids ?? "";
                        $questionKey = $questionId;
                        // echo "<p>Question_$questionKey: $value</p>";
                        echo "<script>questions['$questionKey']='$value';</script>";
                    } elseif($control == 'ranking-static'){
                        foreach($staticAnswer as $index => $subQuestion1){
                            $value = $line[$subQuestion1][0]->response_values ?? "";
                            $questionKey = "{$questionId}_{$subQuestion1}";
                            // echo "<p>Question_$questionKey: $value</p>";
                            echo "<script>questions['$questionKey']='$value';</script>";
                        }
                    }else {
                        foreach($dynamicAnswerRows as $subQuestionGroups){
                            foreach($subQuestionGroups as $subQuestion1){
                                // dump($subQuestion1);
                                if(!$showSubQuestion2){
                                    $value = $line[$subQuestion1['id']][0]->response_ids ?? "";
                                    $questionKey = "{$questionId}_{$subQuestion1['id']}";
                                    // echo "<p>Question_$questionKey: $value</p>";
                                    echo "<script>questions['$questionKey']='$value';</script>";
                                } else {
                                    $value = $line[$subQuestion1['id']][$subQuestion2['id']]->response_ids ?? "";
                                    $questionKey = "{$questionId}_{$subQuestion1['id']}_{$subQuestion2['id']}";
                                    // echo "<p>Question_$questionKey: $value</p>";
                                    echo "<script>questions['$questionKey']='$value';</script>";
                                }
                            }
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
                    :line="$line"
                    />', [
                        'questionId' => $questionId,
                        'staticAnswer' => $staticAnswer,
                        'dynamicAnswerRows' => $dynamicAnswerRows,
                        'dynamicAnswerRowGroups' => $dynamicAnswerRowGroups,
                        'renderAsRows' => $renderAsRows,
                        'subQuestion2Id' => $subQuestion2['id'] ?? null,
                        'line' => $line,
                    ]);
                }
            }
        @endphp
    </div>
</div>
 
@once
<script>
    const reRenderRankTable = (questionId, count) => {
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

        // console.log(array)
    }
</script>
@endonce