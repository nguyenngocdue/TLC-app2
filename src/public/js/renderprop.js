$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
})
$(document).ready(function () {
    $('.btn-delete-user').click(function () {
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
                        Swal.fire('Deleted!', response.message, 'success')
                        setTimeout(location.reload.bind(location), 1000)
                    },
                    error: function (jqXHR, textStatus, errorThrown) {},
                })
            }
        })
    })
})
