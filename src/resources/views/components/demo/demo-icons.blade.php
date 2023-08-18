<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Widgets">
        {{-- <div class="grid gap-6 mb-8 md:grid-cols-2 "> --}}
       <x-renderer.table maxH={{null}} :columns="$columns" :dataSource="$dataSource" groupBy="name" groupByLength=2 showNo=1/>
        {{-- </div> --}}
    </x-renderer.card>
   
</div>