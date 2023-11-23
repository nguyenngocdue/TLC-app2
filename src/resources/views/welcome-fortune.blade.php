@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<x-renderer.kanban.page />
<br/>


<form id="myForm" action="submit.php" method="post" enctype="multipart/form-data">
    <input type="text" name="name" />
    <input type="email" name="email" />
    <input type="file" name="attachment" />
    <input type="submit" value="Submit" />
</form>

<script>
$(document).ready(function() {
    $('#myForm').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        var formData = new FormData(this);
console.log(formData);
        // Perform any additional actions or validations here if needed
        
        // AJAX Submission Example
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            contentType: false, // Necessary when sending FormData
            processData: false, // Necessary when sending FormData
            success: function(response) {
                // Handle success response
                console.log('Form submitted successfully!');
                // You can handle the response from the server here
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error('Error occurred while submitting the form:', error);
            }
        });
    });
});
</script>
@endsection