@php
    $classButton = "bg-gray-200 p-1 shadow rounded text-xs w-full focus:border-1 bold my1-1";
@endphp

<script>const route_bucket = "{{$routeBucket}}";</script>

<div>
    <x-renderer.card title="All Pages"> 
        <div id="toc_group_1" data-id="toc_group" class="grid gap-1">
            @foreach($buckets as $bucket)
                <x-renderer.kanban.bucket :bucket="$bucket" groupWidth="{{$groupWidth}}" />
            @endforeach
        </div>
        <script>kanbanInit1("toc_group_", [1], route_bucket, "categoryGroup")</script>
        
        <input id="txtCurrentPage" type="hidden" value="{{$pageId}}"/>
        <button class="{{$classButton}} px-4 " type="button" onclick="addANewKanbanObj('toc_group_', 1, route_bucket, '{{$groupWidth}}')">+ Add a Bucket</button>
    </x-renderer.card>
</div>

@once
<script>
    const convertSecondsToTime = (seconds) => {
        if(undefined === seconds) return "unknown";
        const hourPerDay = 8.5
        const days = Math.floor(seconds / (3600 * hourPerDay));
        const hours = Math.floor((seconds % (3600 * hourPerDay)) / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const remainingSeconds = seconds % 60;

        let result = '';
        if (days > 0) {
            result += `${days} ${days==1?'day':'days'} + `;
        }

        result += `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;

        return result;
    }

    const taskInterval = (taskId) => {
        const timeCountingType = $('#taskParentTimeCountingType_' + taskId).val()
        let result = ""
        let groupId = 0
        switch(timeCountingType)
        {
            case '1': 
                groupId = $("#taskParentId_" + taskId).val()
                if(undefined === currentElapsed[groupId]) currentElapsed[groupId]={}
                if(!currentElapsed[groupId][taskId]) currentElapsed[groupId][taskId] = {}
                if(!currentElapsed[groupId][taskId]['elapsed']) currentElapsed[groupId][taskId]['elapsed'] = 0
                currentElapsed[groupId][taskId]['elapsed']++
                elapse = convertSecondsToTime(currentElapsed[groupId][taskId]['elapsed'])
                result = "Elapsed: <span class='text-blue-600'>" + elapse + "<span>"
                break;
            case '2':
                groupId = $("#taskParentPreviousGroupId_" + taskId).val()
                elapse = convertSecondsToTime(currentElapsed[groupId][taskId]['elapsed'])
                result = "Took: <span class='text-blue-800 font-bold'>" + elapse + "</span>"
                
                groupId = $("#taskParentRectifiedGroupId_" + taskId).val()
                // console.log(groupId, currentElapsed[groupId])
                if(currentElapsed[groupId]){ // Never rectified before
                    rectified = convertSecondsToTime(currentElapsed[groupId][taskId]['elapsed'])
                    result += "<br/>Rectified: " + rectified;
                }
                break;
            // case '3':
            //     groupId = $("#taskParentId_" + taskId).val()
            //     if(undefined === currentElapsed[groupId]) currentElapsed[groupId]={}
            //     if(!currentElapsed[groupId][taskId]) currentElapsed[groupId][taskId] = 0
            //     // currentElapsed[groupId][taskId]++
            //     elapse = convertSecondsToTime(currentElapsed[groupId][taskId])
            //     result = "Closed: <span class='text-blue-600'>" + elapse + "<span>"
            //     break;
            default:
                break;
        }
        // console.log(taskId, currentElapsed[taskId])
        $("#taskElapseTxt_"+taskId).html(result)
        // $('#elapse_'+taskId).html(result)
    }

    const currentElapsed = {};
</script>
@endonce