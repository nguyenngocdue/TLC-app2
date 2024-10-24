@extends('layouts.app')
@section('topTitle', '')
@section('title', 'Acrylic tank calculation')

@section('content')
<table class="border border-gray-300 rounded p-2 m-2">
    <tr>
        <td>
            Tank Length: 
        </td>
        <td>
            <input type="number" id="tank_length" class="rounded" onchange="recalc()"/>
        </td>
        <td>
            (mm)
        </td>
    </tr>
    <tr>
        <td>
            Tank Width: 
        </td>
        <td>
            <input type="number" id="tank_width" class="rounded" onchange="recalc()"/>
        </td>
        <td>
            (mm)
        </td>
    </tr>
    <tr>
        <td>
            Tank Height: 
        </td>
        <td>
            <input type="number" id="tank_height" class="rounded" onchange="recalc()"/>
        </td>
        <td>
            (mm)
        </td>
    </tr>
    <tr>
        <td>
            Water Depth: 
        </td>
        <td>
            <input type="number" id="water_depth" class="rounded" onchange="recalc()"/>
        </td>
        <td>
            (mm)
        </td>
    </tr>
    <tr></tr>
        <td>            
            Maximun Water Pressure:
        </td>
        <td>
            <input type="number" id="max_water_pressure" class="rounded readonly" readonly/>
        </td>
        <td>
            (psi)
        </td>
    </tr>
    <tr>
        <td>            
            L/H: 
        </td>
        <td>
            <input type="number" id="l_h" class="rounded readonly" readonly/>
        </td>
    </tr>
    <tr>
        <td>            
            Maximun Allowable Stress for Acrylic Cast: 
        </td>
        <td>
            <input type="number" id="max_allowable_stress" class="rounded readonly" value="750" readonly/>
        </td>
        <td>
            (psi)
        </td>
    </tr>
    <tr>
        <td>            
            b: 
        </td>
        <td>
            <input type="number" id="b" class="rounded readonly" readonly/>
        </td>
    </tr>
    <tr>
        <td>            
            Water Volume:
        </td>
        <td>
            <input type="number" id="water_volume" class="rounded" readonly/> 
        </td>
        <td>
            (mm)
        </td>
    </tr>
    <tr>
        <td>            
            Minimum Thickness with Brace: 
        </td>
        <td>
            <input type="number" id="min_thickness_with_brace" class="rounded" readonly/> 
        </td>
        <td>
            (mm)
        </td>
    </tr>
    <tr>
        <td>            
            Minimum Thickness rimless: 
        </td>
        <td>
            <input type="number" id="min_thickness_rimless" class="rounded" readonly/> 
        </td>
        <td>
            (mm)
        </td>
    </tr>
</table>
@endsection

<script>
function recalc(){
    var tank_length = document.getElementById('tank_length').value;
    var tank_width = document.getElementById('tank_width').value;
    var tank_height = document.getElementById('tank_height').value;
    var water_depth = document.getElementById('water_depth').value;

    var max_water_pressure = 0.0361 * water_depth;
    var l_h = water_depth == 0 ? 0 : tank_length / water_depth;
    var max_allowable_stress = document.getElementById('max_allowable_stress').value;
    if(l_h == 0) {
        var b = 0;
    } else {
        if(l_h > 4) {
            var b = 0.94;
        } else {
            var b = -0.005 * Math.pow(l_h, 5) + 0.0606 * Math.pow(l_h, 4) - 0.2725 * Math.pow(l_h, 3) + 0.4849 * Math.pow(l_h, 2) + 0.0553 * l_h - 0.0021;
        }        
    }
    var min_thickness_with_brace = Math.pow(b * max_water_pressure * Math.pow(tank_height, 2) / max_allowable_stress, 0.5);    
    var min_thickness_rimless = 1.5 * Math.pow(b * max_water_pressure * Math.pow(tank_height, 2) / max_allowable_stress, 0.5);
    
    document.getElementById('max_water_pressure').value = max_water_pressure;
    document.getElementById('l_h').value = l_h;
    document.getElementById('max_allowable_stress').value = max_allowable_stress;
    document.getElementById('b').value = b;
    document.getElementById('water_volume').value = tank_length * tank_width * water_depth;
    document.getElementById('min_thickness_with_brace').value = min_thickness_with_brace;
    document.getElementById('min_thickness_rimless').value = min_thickness_rimless;
}
</script>
