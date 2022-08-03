<div class="modal fade" id="modal-upload">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{route('uploadfiles.store')}}" id="form-upload" method="POST" enctype="multipart/form-data">
                @csrf
				<div class="modal-header">
					<h4 class="modal-title">Select File Upload</h4>
				</div>
				<div class="modal-body">
                    <input type="hidden" id="user" name="user" value="{{Auth::user()->id}}">
					<input type="file" id="file" name="files[]" multiple>   
				</div>
				<div class="modal-footer">
					{{-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> --}}
					<button type="submit" class="btn btn-success">Upload File</button>
				</div>
			</form>
		</div>
	</div>
</div>
