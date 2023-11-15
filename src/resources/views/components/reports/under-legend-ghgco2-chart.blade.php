<ul class=" flex  items-start  {{$classUL ?? 'flex-col'}}">
    <li class="flex items-center pb-2">
        <div class="w-12 h-4 bg-[#4dc9f6] {{$class}}"></div>
       <p class='pl-4 text-[#1A401F] text-sm font-roboto font-normal'><strong>Scope 1:</strong> <strong>{{$info['direct_emissions']['tco2e']}}</strong> tCO2e (<strong>{{$info['direct_emissions']['percent']}}%</strong>).</p>
    </li>
    <li class="flex items-center pb-2">
        <div class="w-12 h-4  bg-[#f67019] {{$class}}"></div>
       <p class='pl-4 text-[#1A401F] text-sm font-roboto font-normal'><strong>Scope 2:</strong> <strong>{{$info['indirect_emissions']['tco2e']}}</strong> tCO2e (<strong>{{$info['indirect_emissions']['percent']}}%</strong>).</p>
    </li>
    <li class="flex items-center pb-2">
        <div class="w-12 h-4  bg-[#f53794] {{$class}}"></div>
        <p class='pl-4 text-[#1A401F] text-sm font-roboto font-normal'><strong>Scope 3:</strong> <strong>{{$info['other_indirect_emissions']['tco2e']}}</strong> tCO2e (<strong>{{$info['other_indirect_emissions']['percent']}}%</strong>).</p>
    </li>
</ul>