@php
    $questionId = $item['id'];
    $validation = $item['validation'];
    // dump($validation);
@endphp
@once
    <script>const questions = {};</script>
@endonce
@once
<script>
    const countCheckedByName = (name) => document.querySelectorAll('input[name="' + name + '"]:checked').length
    const validationType = (id) => {
        const x = {
            450: 'none',
            451: 'required',
            452: 'min_3',
        };
        return x[id]
    }
    const validationPassOrFail = (validation, value) => {
        const vType = validationType(validation)
        // console.log(vType)
         switch(vType){
            case 'required': 
                // console.log(value)
                return !!value
            case 'min_3':
                return value >=3
            case 'none':
            default:
                return true;
        }
    }
    const refreshValidation = (questionKey, validation, value) => {
        const pass = validationPassOrFail(validation, value)
        // console.log(questionKey, validation, value, pass ? "PASS" : "FAIL")
        questions[questionKey] = pass
        if(pass){
            $("#" + questionKey + "_pass").show()
            $("#" + questionKey + "_fail").hide()
        } else {
            $("#" + questionKey + "_pass").hide()
            $("#" + questionKey + "_fail").show()
        }
    }
</script>
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
                case 'checkbox-dydynamic':
                    $control = 'checkbox-dynamic';
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
                    :validation="$validation"
                    :gridCols="$gridCols"
                    
                    />', [
                        'questionId' => $questionId,
                        'staticAnswer' => $staticAnswer,
                        'dynamicAnswerRows' => $dynamicAnswerRows,
                        'dynamicAnswerRowGroups' => $dynamicAnswerRowGroups,
                        'renderAsRows' => $renderAsRows,
                        'subQuestion2Id' => $subQuestion2['id'] ?? null,
                        'line' => $line,
                        'validation' => $validation,
                        'gridCols' => $item['grid_cols'] ?? 12,
                        // 'questionJSKey' => $questionJSKey,
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
