{{$id}}
<div id="{{$id}}_pass" class="hidden1">
    <i class=" fa-solid fa-square-check text-green-600 text-3xl mr-2"></i>
</div>
<div id="{{$id}}_fail" class="hidden1">
    <i class=" fa-solid fa-square-u text-red-600 text-3xl mr-2"></i>
</div>

<script>
    refreshValidation("{{$id}}", "{{$validation}}", "{{$selected}}")
</script>