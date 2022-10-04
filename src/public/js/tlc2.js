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
    $('.btn-delete').click(function () {
        var url = $(this).attr('data-url')
        var _this = $(this)
        console.log(url)
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
    $('#listMenus li a').click(function (e) {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active')
        } else {
            $(this).addClass('active')
        }
    })
    $('.checkbox-toggle').click(function (e) {
        var hideColumn = $(this).attr('name')
        if ($('.checkbox-toggle').is(':checked')) {
            $('.' + hideColumn + '_th').toggle(this.checked)
            $('.' + hideColumn + '_td').toggle(this.checked)
        }
    })
    $('.checkbox-toggle').each(function (i, e) {
        var hideColumn = $(e).attr('name')
        if (!$(e).is(':checked')) {
            $('.' + hideColumn + '_th').hide()
            $('.' + hideColumn + '_td').hide()
        }
    })
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
$(document).ready(function () {
    $('.select-all').click(function () {
        var model = $(this).attr('data-model')
        var set_checked = !$('.check-' + model)
            .first()
            .is(':checked')
        $('.check-' + model).prop('checked', set_checked)
    })
})
