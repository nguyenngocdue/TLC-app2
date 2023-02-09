<div class="input-group">
    <div class="input-group-prepend ">
      <span class="input-group-text border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
        <i class="far fa-clock"></i>
      </span>
    </div>
    <input type="text" class="form-control float-right bg-white  border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" id="{{$name}}" name="{{$name}}" value="{{$value}}">
  </div>
<script>
    $('[id="'+"{{$name}}"+'"]').daterangepicker({
            autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            timePickerSeconds: true,
            locale: {
                        format: 'HH:mm:ss'
                    }
    })
    $('[id="'+"{{$name}}"+'"]').attr("placeholder","Start time  ->  End time");

    $('[id="'+"{{$name}}"+'"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('HH:mm:ss') + ' - ' + picker.endDate.format('HH:mm:ss'));
    });

    $('[id="'+"{{$name}}"+'"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    $('[id="'+"{{$name}}"+'"]').on('show.daterangepicker', function (ev, picker) {
            picker.container.find(".calendar-table").hide();
        });
</script>