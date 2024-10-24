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
            <input type="number" id="tank_length" class="rounded" onchange="recalc()" value="600"/>
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
            <input type="number" id="tank_width" class="rounded" onchange="recalc()" value="400"/>
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
            <input type="number" id="tank_height" class="rounded" onchange="recalc()" value="400"/>
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
            <input type="number" id="water_depth" class="rounded" onchange="recalc()" value="350"/>
        </td>
        <td>
            (mm)
        </td>
    </tr>
    <tr>

    </tr>
    <tr>
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
            Maximum Allowable Stress for Acrylic Cast: 
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
            <input type="number" id="water_volume" class="rounded readonly" readonly/> 
        </td>
        <td>
            (L)
        </td>
    </tr>
    <tr>
        <td>            
            Minimum Thickness with Brace: 
        </td>
        <td>
            <input type="number" id="min_thickness_with_brace" class="rounded readonly" readonly/> 
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
            <input type="number" id="min_thickness_rimless" class="rounded readonly" readonly/> 
        </td>
        <td>
            (mm)
        </td>
    </tr>
    <tr>
        <td>
            Recommended thickness material (with Brace):
        </td>
        <td>
            <input type="number" id="thickness_material_with_brace" class="rounded readonly" readonly/>
        </td>
        <td>
            (mm)
        </td>
    </tr>
    <tr>
        <td>
            Recommended thickness material (rimless):
        </td>
        <td>
            <input type="number" id="thickness_material_rimless" class="rounded readonly" readonly/>
        </td>
        <td>
            (mm)
        </td>
    </tr>
    <tr>
        <td>
            Price (with Brace):
        </td>
        <td>
            <input type="number" id="price_with_brace" class="rounded readonly" readonly/>
        </td>
        <td>
            (VND)
        </td>
    </tr>
    <tr>
        <td>
            Price (rimless):
        </td>
        <td>
            <input type="number" id="price_rimless" class="rounded readonly" readonly/>
        </td>
        <td>
            (VND)
        </td>
    </tr>
</table>


<script>
function findClosestValue(inputValue, validValues) {
    // Find the closest value as before
    const closestValue = validValues.reduce((prev, curr) => {
        return (Math.abs(curr - inputValue) < Math.abs(prev - inputValue) ? curr : prev);
    });

    // If the difference is greater than 0.25, round up to the next valid value
    if (Math.abs(closestValue - inputValue) > 0.25) {
        // Find the next valid value that is greater than the inputValue
        const higherValues = validValues.filter(val => val > inputValue);
        return higherValues.length > 0 ? Math.min(...higherValues) : closestValue;
    }

    return closestValue;
}
function recalc(){
    var mm_to_in = 0.0393701;
    var tank_length_mm = document.getElementById('tank_length').value;
    var tank_width_mm = document.getElementById('tank_width').value;
    var tank_height_mm = document.getElementById('tank_height').value;
    var water_depth_mm = document.getElementById('water_depth').value;

    var tank_length = tank_length_mm * mm_to_in;
    var tank_width = tank_width_mm * mm_to_in;
    var tank_height = tank_height_mm * mm_to_in;
    var water_depth = water_depth_mm * mm_to_in;

    // var P = 1000 * 9.81 / 6894.76 / 3.28084 = 0.0361;
    // P = ρ * g * h / 6894.76 / 3.28084
    // 6894.76 = 1 PSI in Pascal
    // 3.28084 = 1 meter in feet

    var max_water_pressure = (0.0361 * water_depth);
    var l_h = water_depth == 0 ? 0 : tank_length / water_depth;
    var max_allowable_stress = document.getElementById('max_allowable_stress').value;
    if(l_h == 0) {
        var b = 0;
    } else {
        if(l_h > 4) {
            var b = 0.94;
        } else {
            var b = -0.005 * Math.pow(l_h, 5) 
                + 0.0606 * Math.pow(l_h, 4) 
                - 0.2725 * Math.pow(l_h, 3) 
                + 0.4849 * Math.pow(l_h, 2) 
                + 0.0553 * Math.pow(l_h, 1)
                - 0.0021;            
        }        
    }
    var min_thickness_with_brace = Math.pow(b * max_water_pressure * Math.pow(water_depth, 2) / max_allowable_stress, 0.5);    
    var min_thickness_rimless = 1.5 * min_thickness_with_brace;

    var min_thickness_with_brace_mm = (min_thickness_with_brace / mm_to_in).toFixed(2)
    var min_thickness_rimless_mm = (min_thickness_rimless / mm_to_in).toFixed(2)
    
    var price_per_mm3 = 0.19277
    var front = (tank_length_mm * tank_height_mm);
    var side = (tank_width_mm * tank_height_mm);
    var bottom = (tank_length_mm * tank_width_mm);

    var validValues = [1, 2, 3, 4, 5, 8, 10, 12, 15, 20, 25, 30, 35, 40, 50, 60, 70, 80, 90, 100];

    var thickness_material_with_brace = findClosestValue(Math.round(min_thickness_with_brace_mm), validValues);
    var thickness_material_rimless = findClosestValue(Math.round(min_thickness_rimless_mm), validValues);

    var price_with_brace = (2 * front + 2 * side + 2 * bottom) * thickness_material_with_brace * price_per_mm3;
    var price_rimless = (2 * front + 2 * side +  bottom) * thickness_material_rimless * price_per_mm3;

    document.getElementById('max_water_pressure').value = max_water_pressure.toFixed(3);
    document.getElementById('l_h').value = l_h.toFixed(3);
    document.getElementById('max_allowable_stress').value = max_allowable_stress;
    document.getElementById('b').value = b.toFixed(3);
    document.getElementById('water_volume').value = (tank_length_mm * tank_width_mm * water_depth_mm / 1000000).toFixed(3);
    document.getElementById('min_thickness_with_brace').value = (min_thickness_with_brace_mm);
    document.getElementById('min_thickness_rimless').value = (min_thickness_rimless_mm);

    document.getElementById('thickness_material_with_brace').value = thickness_material_with_brace;
    document.getElementById('thickness_material_rimless').value = thickness_material_rimless;

    document.getElementById('price_with_brace').value = price_with_brace.toFixed(0);
    document.getElementById('price_rimless').value = price_rimless.toFixed(0);
}

recalc()
</script>

@endsection