<x-renderer.progress-bar :dataSource="$progressData" height="40px" />
      
<script>
    const table01Name = '{{$table01Name}}';
    const checkPointIds = @json($dataSource);
    const checkpoint_names = {}

    $(document).ready(()=> {
        checkpoint_names[table01Name] = checkPointIds.map(id=> table01Name+'[{{$categoryName}}]['+id+']');
        updateProgressBar2(table01Name)
    })
</script>