@once
<script>
  function loadDocs(data){
      console.log("Loading",data)
      const url = "/api/v1/entity/render_block";
      $.ajax({
          // type:'POST',
          url,
          data,
          success: (response)=>{
            $("#divMain").html(response.hits)
          },
          error:(response)=>{
              // console.log(response.responseJSON.message)
              const message = response.responseJSON.message
              $("#divMain").html("<b class='text-red-500'>"+message+"</b>")
          }
      })
    }
</script>
@endonce

@extends("modals.modal-large")

@section($modalId.'-header', "Document List")

@section($modalId.'-body')
<div id="divMain" class="m-2 text-center">
    <div class="my-60">
        <i class="fa-duotone fa-spinner fa-spin text-green-500 mx-2"></i>Loading data...
    </div>
</div>
@endsection


@section($modalId.'-javascript')
<script>
    setTimeout(()=> loadDocs(getModalParams('{{$modalId}}')), 100)
</script>
{{-- <script src="{{ asset('js/modals/modal-report-chart.blade.js') }}"></script> --}}
@endsection


@section($modalId.'-footer')

@endsection 