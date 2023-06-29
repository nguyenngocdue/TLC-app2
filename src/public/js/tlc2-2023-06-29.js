//Setup for any Ajax requests need to login
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), }, })

function callApiStoreEmpty(url, data, callback = null) {
    $.ajax({
        type: 'post',
        url,
        data: { lines: data },
        success: function (response) {
            if (callback) {
                callback(response);
            } else {
                toastr.success(response.message);
                window.location.replace(response.hits[0]['redirect_edit_href']);
            }
        },
        error: function (jqXHR) {
            toastr.error(jqXHR.responseJSON.message);
        },
    })
}