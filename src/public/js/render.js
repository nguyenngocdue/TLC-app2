$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
})
$(document).ready(function () {
    $('.btn-edit').click(function (e) {
        var url = $(this).attr('data-url')
        console.log(url)
        $.ajax({
            type: 'get',
            url: url,
            success: function (response) {
                console.log(response)
                $('#name_role').val(response.data.name)
                $('#guard_role').val(response.data.guard_name)
                $('#form-edit').attr(
                    'action',
                    '/admin/' + response.type + '/' + response.data.id
                )
            },
            error: function (error) {},
        })
    })
    $('.select-all').click(function () {
        var model = $(this).attr('data-model')
        var set_checked = !$('.check-' + model)
            .first()
            .is(':checked')
        $('.check-' + model).prop('checked', set_checked)
    })
    $('.btn-delete-render').click(function () {
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
