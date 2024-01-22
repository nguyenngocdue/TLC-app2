@php
// dump($table01Name);
// dump($dataSource);
echo "<script>";
echo "const checkpoint_names={};";
echo "checkpoint_names['$table01Name'] = [";
foreach($dataSource as $line){
    if($line->control_type_id === 4) { // 4: RADIO
        $column = $line::$eloquentParams['getControlValue'][2];
        $name = $table01Name."[".$column."][".$line->id."]";
        // dump($line);
        echo ('"'.$name.'",');
    }
}
echo "]";
echo "</script>";

$progressData = [
    [
        'id' => 'yes',
        'color' => 'green',
        'percent' => '-1%',
        // 'label' => 'Yes',
    ],
    [
        'id' => 'no',
        'color' => 'pink',
        'percent' => '-1%',
        // 'label' => 'No',
    ],
    [
        'id' => 'na',
        'color' => 'gray',
        'percent' => '-1%',
        // 'label' => 'N/A',
    ],
    [
        'id' => 'on_hold',
        'color' => 'orange',
        'percent' => '-1%',
        // 'label' => 'On Hold',
    ],
];
@endphp
<x-renderer.progress-bar :dataSource="$progressData" height="40px" />
      
@once
<script>
function updateProgressBar(table01Name){
    console.log("updateProgressBar", table01Name)    
    // $("input[name='table01[hse_insp_control_value_id][4135]']:checked").val();
    const all = checkpoint_names[table01Name].length
    const values = {}
    checkpoint_names[table01Name].forEach(name=>{
        const val = $("input[name='"+name+"']:checked").val()
        if(values[val] === undefined) values[val] = 0
        values[val]++
    })
    // const percent = Object.keys(values).map((key) => Math.round( 100 * values[key] / all))
    const percent = {}
    Object.keys(values).map((key) => percent[key]= Math.round( 100 * values[key] / all))

    console.log(values, percent)
    if(percent[1]){
        $("#progress_yes").html(`<span class='w-full'>Yes<br/>${values[1]}/${all}</span>`).css({width: percent[1] +'%'}).show()
    }else {
        $("#progress_yes").html("").hide()
    }
    if(percent[2]){
        $("#progress_no").html(`<span class='w-full'>No<br/>${values[2]}/${all}</span>`).css({width: percent[2] +'%'}).show()
    }else {
        $("#progress_no").html("").hide()
    }
    if(percent[3]){
        $("#progress_na").html(`<span class='w-full'>N/A<br/>${values[3]}/${all}</span>`).css({width: percent[3] +'%'}).show()
    }else {
        $("#progress_na").html("").hide()
    }
    if(percent[4]){
        $("#progress_on_hold").html(`<span class='w-full'>On Hold<br/>${values[4]}/${all}</span>`).css({width: percent[4] +'%'}).show()
    }else {
        $("#progress_on_hold").html("").hide()
    }

    // $("#progress_no").css({width: (percent[2] ? percent[2] : 0 ) +'%'})
    // $("#progress_na").css({width: (percent[3] ? percent[3] : 0 )+'%'})
    // $("#progress_on_hold").css({width: (percent[4] ? percent[4] : 0 )+'%'})
}
</script>

@endonce
<script>
    $(document).ready(()=>{
        updateProgressBar('{{$table01Name}}')
    })
</script>