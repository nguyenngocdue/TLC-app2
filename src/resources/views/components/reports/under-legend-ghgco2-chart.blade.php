<ul class=" flex flex-col items-start">
    <li class="flex items-center pb-2">
        <div class="w-12 h-4 bg-[#4dc9f6] "></div>
       <p class='pl-4 text-gray-600 font-semibold leading-tight text-xltext-center dark:text-gray-300'>Scope 1: <strong>{{$info['direct_emissions']['tco2e']}}</strong> tCO2e (<strong>{{$info['direct_emissions']['percent']}}%</strong>).</p>
    </li>
    <li class="flex items-center pb-2">
        <div class="w-12 h-4  bg-[#f67019] "></div>
       <p class='pl-4 text-gray-600 font-semibold leading-tight text-xltext-center dark:text-gray-300'>Scope 2: <strong>{{$info['indirect_emissions']['tco2e']}}</strong> tCO2e (<strong>{{$info['indirect_emissions']['percent']}}%</strong>).</p>
    </li>
    <li class="flex items-center pb-2">
        <div class="w-12 h-4  bg-[#f53794] "></div>
        <p class='pl-4 text-gray-600 font-semibold leading-tight text-xltext-center dark:text-gray-300'>Scope 3:<strong>{{$info['other_indirect_emissions']['tco2e']}}</strong> tCO2e (<strong>{{$info['other_indirect_emissions']['percent']}}%</strong>).</p>
    </li>
</ul>