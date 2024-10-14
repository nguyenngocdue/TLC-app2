@foreach($notifyTo as $notifyToItem)
    <label class="cursor-pointer">
        <input type="radio" 
            class="mx-1" 
            name="notify_to" 
            value="{{$notifyToItem->id}}" 
            onchange="reloadNotifyTo({{$notifyToItem->id}})"
            id="{{$notifyToItem->id}}"
            @checked($notifyToItem->id == $notifyToId)
            />
        {{$notifyToItem->name}}
    </label>
    {{-- {{$notifyToItem->id ." ". $notifyToId}} --}}
    <br/>    
@endforeach

<div id="json_tree_2_container" class="my-2 rounded border hidden">
    <div class="flex border rounded">                
        <input id="txt-search-box-2" class="p-2 w-full" placeholder="Search Tree"/>
        <button disabled class="p-2 bg-blue-500 text-white rounded"><i class="fa fa-search"></i></button>
    </div>
    <div id="json_tree_2"></div>
</div>

<script>
    updatePPRoute = '{{$updatePPRoute}}';
    loadDynamicNotifyToTree = '{{$loadDynamicNotifyToTree}}'
    ppId = {{$ppId}};
    notifyToId = {{$notifyToId}};
    
    function updateNotifyTo(notifyToId, jsonTree2, notifyToHodExcluded, notifyToMemberExcluded){
        console.log("notifyToId", notifyToId);
        var jsonTree2a = null
        switch(notifyToId){
            case 756: // None
                $('#json_tree_2_container').hide();
                console.log("None: Hide the tree");
                return;
            case 757: // Everyone
                $('#json_tree_2').jstree("destroy").empty();
                jsonTree2a = JSON.parse(JSON.stringify(jsonTree2));
                $('#json_tree_2_container').show();                
                break;
            case 758: // HOD
                $('#json_tree_2').jstree("destroy").empty();
                jsonTree2a = JSON.parse(JSON.stringify(jsonTree2))
                    .filter(node => node.id.startsWith('hod_') || node.id.startsWith('department_'));
                // console.log("filter only HOD", jsonTree2a);
                $('#json_tree_2_container').show();                
                break;
                case 759: // Member
                $('#json_tree_2').jstree("destroy").empty();
                jsonTree2a = JSON.parse(JSON.stringify(jsonTree2))
                .filter(node => node.id.startsWith('member') || node.id.startsWith('department_'));//Both members_ and member_
                // console.log("filter only Members", jsonTree2a);
                $('#json_tree_2_container').show();                
                break;
            default:
                console.log("Invalid notifyToId", notifyToId);
                return;
        }
        
        // console.log("new jsonTree2a", jsonTree2a);
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

                // console.log("Unchecked HOD nodes:", uncheckedHodNodes);
                // console.log("Unchecked Member nodes:", uncheckedMemberNodes);
                // console.log(updatePPRoute, ppId)
                
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
                        toastr.error("error", error.responseJSON.message);
                    }
                })
            })  
        });  
    } 
    function reloadNotifyTo(notifyToId){
        $.ajax({
            url: updatePPRoute,
            type: 'POST',
            data: {
                id: ppId,
                notify_to: notifyToId,
            },
            success: function(response) {
                console.log("response", response);
            },
        })
        // console.log("reloadNotifyTo", notifyToId);
        $.ajax({
            url: loadDynamicNotifyToTree,
            type: 'GET',
            data:{
                ppId,
            },
            success: function(response){
                const {jsonTree, notifyToHodExcluded, notifyToMemberExcluded} = response;
                updateNotifyTo(notifyToId, jsonTree, notifyToHodExcluded, notifyToMemberExcluded);
            },
            error: function(error) {
                toastr.error("error", error.responseJSON.message);
            }
        })
    }
    reloadNotifyTo(notifyToId)
</script>