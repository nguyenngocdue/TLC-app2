<div class="relative w-full">
  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none ">
    <i class="fa-duotone fa-calendar"></i>
  </div>
  <input 
      value="{{$value}}" name={{$name}} id={{$name}} 
      datepicker 
      datepicker-autohide 
      component="controls/datepicker3"
      type='text'
      placeholder="DD/MM/YYY" 
      datepicker-format="dd/mm/yyyy" 
      class="datepicker bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
      onblur='onChangeDropdown2("{{$name}}")'
      {{-- onchange='onChangeDropdown2("{{$name}}")' --}}
      >

</div>

{{-- https://flowbite.com/docs/plugins/datepicker/ --}}
