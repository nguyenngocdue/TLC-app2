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
    ],
    [
        'id' => 'no',
        'color' => 'pink',
        'percent' => '-1%',
    ],
    [
        'id' => 'na',
        'color' => 'gray',
        'percent' => '-1%',
    ],
    [
        'id' => 'on_hold',
        'color' => 'orange',
        'percent' => '-1%',
    ],
];
@endphp
<x-renderer.progress-bar :dataSource="$progressData" height="40px" />
      
<script>
    $(document).ready(()=> updateProgressBar('{{$table01Name}}'))
</script>