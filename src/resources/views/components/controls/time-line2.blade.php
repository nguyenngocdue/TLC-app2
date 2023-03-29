
<div class="md:flex text-center ml-10 mr-10 md:ml-36 md:mr-36 justify-center">
    <div class="items-center">
        <div class="relative mt-5 text-left">
            @foreach($dataSource as $key => $value)
                <x-controls.time-line-item2 :dataSource="$value"/>
            @endforeach                 
        </div>
    </div>

</div>



