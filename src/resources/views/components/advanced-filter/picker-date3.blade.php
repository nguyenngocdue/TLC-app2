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
        autoUpdateInput: false,
        locale: {
          format: 'DD/MM/YYYY',
        },
      }
    )
    $('[id="'+"{{$name}}"+'"]').attr("placeholder","Start date  ->  End date");
    $('[id="'+"{{$name}}"+'"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('[id="'+"{{$name}}"+'"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
</script>