<form id="refresh_{{$type}}" action="{{$route}}" method="post">
    @method('PUT')
    @csrf
    <input type="hidden" name='_entity' value="{{$type}}">
    @php
        $valueType = $valueRefresh ? 'refresh' : '';
    @endphp
    <x-renderer.button id="button_refresh" htmlType="button" onClick="updateRefreshPage()" type='{{$valueType}}' class="bg-blue-500"><i class="fa-sharp fa-light fa-rotate-right"></i> Refresh (60)</x-renderer.button>
</form>
@if($valueRefresh)
<script>
    let count = 60;
    const countdownInterval = setInterval(function() {
      count--;
      document.getElementById('button_refresh').innerHTML = '<i class="fa-sharp fa-light fa-rotate-right"></i> Refresh (' + count + ')';
      if(count == 0){
        clearInterval(countdownInterval);
        location.reload();
      }
    }, 1000);
  </script>
@endif
<script>
    function updateRefreshPage(){
            $('[id="refresh_'+"{{$type}}"+'"]').append('<input type="hidden" name="action" value="updateRefreshPage">')
            $('[id="refresh_'+"{{$type}}"+'"]').submit()
        }
</script>

