<div class="input-group">
    <div class="input-group-prepend">
      <span class="{{$classListInputGroupText}}">
        <i class="far fa-calendar-alt"></i>
      </span>
    </div>
    <input type="text" autocomplete="off" class="{{$classListFormInput}}" id="{{$name}}" name="{{$name}}" value="{{$value}}">
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