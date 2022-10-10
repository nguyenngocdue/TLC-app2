$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
})
$(document).ready(function () {
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

    $('#listMenus li a').click(function (e) {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active')
        } else {
            $(this).addClass('active')
        }
    })
    $('.checkbox-toggle').click(function (e) {
        var hideColumn = $(this).attr('name')
        var result = hideColumn.split('|')[1]
        if ($('.checkbox-toggle').is(':checked')) {
            $('.' + result + '_th').toggle(this.checked)
            $('.' + result + '_td').toggle(this.checked)
        }
    })
    $('.checkbox-toggle').each(function (i, e) {
        var hideColumn = $(e).attr('name')
        var result = hideColumn.split('|')[1]
        if (!$(e).is(':checked')) {
            $('.' + result + '_th').hide()
            $('.' + result + '_td').hide()
        }
    })
})
