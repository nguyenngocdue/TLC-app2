<form id="refresh_{{$type}}" action="{{$route}}" method="post" class="mb-0">
    @method('PUT')
    @csrf
    <input type="hidden" name='_entity' value="{{$type}}">
    @php
        $valueType = $valueRefresh ? 'refresh' : '';
    @endphp
    <x-renderer.button 
        title="Toggle automatic refresh every {{$timeout}} seconds."
        id="button_refresh" icon="fa-sharp fa-light fa-rotate-right" 
        htmlType="button" onClick="updateRefreshPage()" 
        type='{{$valueType}}' class="bg-blue-500 mr-1"
        >Refresh ({{$timeout}})</x-renderer.button>
</form>
@if($valueRefresh)
<script>
    let count = {{$timeout}};
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

