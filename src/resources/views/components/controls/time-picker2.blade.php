<div class="input-group">
    <div class="input-group-prepend">
      <span class="input-group-text">
        <i class="far fa-clock"></i>
      </span>
    </div>
    <input type="text" class="form-control float-right" id="{{$name}}" name="{{$name}}" value="{{$value}}">
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