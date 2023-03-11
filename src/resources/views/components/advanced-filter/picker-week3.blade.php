<div class="input-group">
    <div class="input-group-prepend">
      <span class="input-group-text border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
        <i class="far fa-calendar-alt"></i>
      </span>
    </div>
    <input type="text" autocomplete="off" class="form-control float-right bg-white  border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" id="{{$name}}" name="{{$name}}" value="{{$value}}">
  </div>
<script>
    $('[id="'+"{{$name}}"+'"]').daterangepicker(
      {
        "showWeekNumbers": true,
        autoUpdateInput: false,
        locale: {
          format: 'WW/YYYY',
        },
      }
    )
    $('[id="'+"{{$name}}"+'"]').attr("placeholder","Start date  ->  End date");
    $('[id="'+"{{$name}}"+'"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val('W'+picker.startDate.format('WW/YYYY') + ' - ' + 'W'+picker.endDate.format('WW/YYYY'));
    });

    $('[id="'+"{{$name}}"+'"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
</script>