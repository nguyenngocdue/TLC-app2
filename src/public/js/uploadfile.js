$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
})
$(document).ready(function () {
    $('.btn-uploadfiles').click(function (e) {
        var url = $(this).attr('data-url')
        $('#modal-upload').modal('show')
        e.preventDefault()
    })
    $('.btn-delete').click(function () {
        var url = $(this).attr('data-url')
        var _this = $(this)
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'delete',
                    url: url,
                    success: function (response) {
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        setTimeout(location.reload.bind(location), 1000)
                    },
                    error: function (jqXHR, textStatus, errorThrown) {},
                })
            }
        })
    })
})
