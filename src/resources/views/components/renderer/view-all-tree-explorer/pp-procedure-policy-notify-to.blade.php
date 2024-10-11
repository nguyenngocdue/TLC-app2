<label class="cursor-pointer"><input type="radio" class="mx-1" name="notify_to" />All Head of Departments (HOD)</label><br/>
<label class="cursor-pointer"><input type="radio" class="mx-1" name="notify_to" />All Team Members</label><br/>
<label class="cursor-pointer"><input type="radio" class="mx-1" name="notify_to" />All HOD + Team Members</label><br/>

<div class="my-2">
    <div id="json_tree_2"></div>
</div>

<script>
    jsonTree2 = @json($notifyTo);    
    $('#json_tree_2').jstree({ 
        core : {
            data : jsonTree2,
        },
        plugins: ['wholerow', 'checkbox'],
    });
    
</script>