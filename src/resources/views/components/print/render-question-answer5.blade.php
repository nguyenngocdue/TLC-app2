
@php
    $owner_id = ($item->owner_id);
    $cuid = (App\Utils\Support\CurrentUser::id());
@endphp
@if($owner_id == $cuid)
    @php
        $dataSource = App\Models\Exam_sheet::getQuestionsOfSheet($item);
        $tableOfContents = $dataSource->map(fn ($i) => $i->getExamTmplGroup)->unique();
        $sheetLines = App\Models\Exam_sheet::groupByQuestionId($item->getSheetLines);
        // $questions = $item->getExamTmpl->getQuestions;
        // dump($questions);
    @endphp
    @foreach($tableOfContents as $index => $group)
        <x-renderer.heading 
            class="sticky top-[68px] px-2 py-2 rounded bg-blue-500 z-[15]" 
            id="exam_group_{{$group->id}}" 
            level=4 
            >{{$group->name}}</x-renderer.heading>
        @foreach($dataSource as $item)
            @php if($item->exam_tmpl_group_id != $group->id) continue; @endphp
            <div class="my-2">
                <x-question-answer.question-answer readOnly={{true}} :item="$item" :line="$sheetLines[$item->id] ?? null"/>
            </div>
        @endforeach
    @endforeach

    {{-- @foreach($questions as $question)
        <x-question-answer.question-answer readOnly={{true}} :item="$question" :line="$sheetLines[$question->id] ?? null"/>
    @endforeach --}}
@else
You cannot print answer sheet of someone else.
@endif