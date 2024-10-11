@foreach($notifyTo as $notifyToItem)
    <label class="cursor-pointer">
        <input type="radio" class="mx-1" name="notify_to" value="{{$notifyToItem->id}}" id="{{$notifyToItem->id}}"/>{{$notifyToItem->name}}
    </label>
    <br/>    
@endforeach

<div class="my-2 rounded border">
    <div class="flex border rounded">                
        <input id="txt-search-box-2" class="p-2 w-full" placeholder="Search Tree"/>
        <button disabled class="p-2 bg-blue-500 text-white rounded"><i class="fa fa-search"></i></button>
    </div>
    <div id="json_tree_2"></div>
</div>

<script>
    updatePPRoute = '{{$updatePPRoute}}';
    ppId = {{$ppId}};
    notifyToId = {{$notifyToId}};
    notifyToHodExcluded = @json($notifyToHodExcluded);
    notifyToMemberExcluded = @json($notifyToMemberExcluded);
    jsonTree2 = @json($notifyToTree);    
    var jsonTree2a = JSON.parse(JSON.stringify(jsonTree2));

    $('#json_tree_2').jstree({ 
        core : {
            data : jsonTree2a,
        },
        plugins: ['wholerow', 'checkbox', 'search'],
    });

    $('#json_tree_2').on('ready.jstree', function() {
        $('#json_tree_2').jstree('check_all');
        notifyToHodExcluded.forEach(function(id) {
            $('#json_tree_2').jstree('uncheck_node', "hod_"+id);
        });
        notifyToMemberExcluded.forEach(function(id) {
            $('#json_tree_2').jstree('uncheck_node', "member_"+id);
        });

        var to = false;
        $('#txt-search-box-2').keyup(function () {
            if(to) { clearTimeout(to); }
            to = setTimeout(function () {
                var v = $('#txt-search-box-2').val();
                $('#json_tree_2').jstree(true).search(v);
            }, 250);
        });
            
        // only listen to change event after check/uncheck all nodes
        $('#json_tree_2').on('changed.jstree', function (e, selectedNode) {    
            const allNodes = $('#json_tree_2').jstree(true).get_json('#', { 'flat': true });
            const uncheckedHodNodes = allNodes
                .filter(node => !selectedNode.selected.includes(node.id))
                .filter(node => node.id.startsWith('hod_'))
                .map(node => 1 * node.id.substr("hod_".length))
            
            const uncheckedMemberNodes = allNodes
                .filter(node => !selectedNode.selected.includes(node.id))
                .filter(node => node.id.startsWith('member_'))
                .map(node => 1 * node.id.substr("member_".length))

            console.log("Unchecked HOD nodes:", uncheckedHodNodes);
            console.log("Unchecked Member nodes:", uncheckedMemberNodes);
            console.log(updatePPRoute, ppId)
            
            $.ajax({
                url: updatePPRoute,
                type: 'POST',
                data: {
                    id: ppId,
                    getNotifyToHodExcluded: uncheckedHodNodes.length > 0 ? uncheckedHodNodes : null,
                    getNotifyToMemberExcluded: uncheckedMemberNodes.length > 0 ? uncheckedMemberNodes : null,                    
                },
                success: function(response) {
                    console.log("response", response);
                },
                error: function(error) {
                    toastr.error("error", error);
                }
            })
        })  
    });   
</script>