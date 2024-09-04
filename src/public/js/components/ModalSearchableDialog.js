function modalSearchableDialogLineRenderer(hit, multipleStr) {
    let line = ''
    line += `<div class="">`
    line += '<label class="hover:bg-gray-200 cursor-pointer w-full p-1 rounded flex gap-1 items-center justify-between">'
    line += `<div class="flex items-center">`
    if (multipleStr) {
        line += `<input type="checkbox" class="mx-1" name="id" value="${hit.id}">`
    } else {
        line += `<input type="radio" class="mx-1" name="id" value="${hit.id}">`
    }
    line += hit.name0
    line += `</div>`
    line += `<span>`
    line += makeId(hit.id)
    line += `</span>`
    line += '</label>'
    line += `</div>`
    return line
}

function modalSearchableDialogInvoke(url, keyword, multipleStr, modalId) {
    // console.log('ModalSearchableDialog.js', url, keyword, modalId)
    $.ajax({
        url,
        type: 'GET',
        data: {
            keyword,
        },
        success: function (response) {
            const hits = response.hits
            const objs = []
            hits.forEach((hit) => objs.push(modalSearchableDialogLineRenderer(hit, multipleStr)))
            $(`#${modalId}_result`).html(objs.join(''))
        },
        error: function (data) {
            // console.log(data);
            toastr.error(data.responseJSON.message)
        },
    })
}
