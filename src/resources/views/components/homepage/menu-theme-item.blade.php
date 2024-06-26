{{-- {{$currentBg}} --}}
<form action="{{$route}}" method="POST" class='flex mb-0'>
    @csrf
    @method('PUT')
    <input type="hidden" name="action" value="updateGlobal">
    <input type="hidden" name="theme-bg" value="{{$color}}-100">
    <input type="hidden" name="theme-text" value="{{$color}}-900">
    <button>  
        <div class="flex font-bold text-sm w-40 hover:bg-{{$color}}-900 hover:text-{{$color}}-100 bg-{{$color}}-100 text-{{$color}}-900 p-2 rounded-l">
            @if($currentBg == $color.'-100')
                <i class="fa fa-star"></i>
            @endif              
            <span class="uppercase mx-auto">light {{$color}}</span>
        </div>
    </button>
</form>

<span class="block w-1 bg-{{$color}}-100"></span>
<span class="block w-1 bg-{{$color}}-200"></span>
<span class="block w-1 bg-{{$color}}-300"></span>
<span class="block w-1 bg-{{$color}}-400"></span>
<span class="block w-1 bg-{{$color}}-500"></span>
<span class="block w-1 bg-{{$color}}-600"></span>
<span class="block w-1 bg-{{$color}}-700"></span>
<span class="block w-1 bg-{{$color}}-800"></span>
<span class="block w-1 bg-{{$color}}-900"></span> 

<form action="{{$route}}" method="POST" class='flex mb-0'>
    @csrf
    @method('PUT')
    <input type="hidden" name="action" value="updateGlobal">
    <input type="hidden" name="theme-bg" value="{{$color}}-900">
    <input type="hidden" name="theme-text" value="{{$color}}-100">
    
    <button>                
        <div class="flex font-bold text-sm w-40 hover:bg-{{$color}}-100 hover:text-{{$color}}-900 bg-{{$color}}-900 text-{{$color}}-100 p-2 rounded-r">
            <span class="uppercase mx-auto">dark {{$color}}</span>
            @if($currentBg == $color.'-900')
            <i class="fa fa-star"></i>
            @endif
        </div>
    </button>
</form>