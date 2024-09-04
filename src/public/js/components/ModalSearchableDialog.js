function renderTag(value) {
    const className =
        'bg-gray-200 text-gray-800 dark:bg-gray-800 dark:text-gray-200  rounded whitespace-nowrap font-semibold text-xs-vw text-xs mx-0.5 px-2 py-1 leading-7 '
    return `<span class="${className}">${value}</span>`
}

function modalSearchableDialogOnSelectHandleValue(id, modalId, multipleStr) {
    const valueName = modalId + '_selectedValue'
    let result = ''
    if (multipleStr) {
        const currentValues = $('#' + valueName).val()
        const currentValuesArray = currentValues ? currentValues.split(',') : []
        const index = currentValuesArray.indexOf(id.toString())
        if (index > -1) currentValuesArray.splice(index, 1)
        else currentValuesArray.push(id)

        result = currentValuesArray.join(',')
    } else {
        result = id
    }
    $('#' + valueName).val(result)
    return result
}

function modalSearchableDialogOnSelectHandleText(id, modalId, multipleStr, newIds, nameField) {
    const valueName = modalId + '_selectedText'
    const newIdArray = newIds ? newIds.split(',') : []
    const newText = newIdArray.map((id) => renderTag(modalSearchableDialogHits[id][nameField])).join('')
    console.log(newIdArray, newText)
    $('#' + valueName).html(newText + '&nbsp')
}

let modalSearchableDialogHits = {}
function modalSearchableDialogOnSelect(id, modalId, multipleStr, nameField) {
    const newIds = modalSearchableDialogOnSelectHandleValue(id, modalId, multipleStr)
    modalSearchableDialogOnSelectHandleText(id, modalId, multipleStr, newIds, nameField)
}

function modalSearchableDialogLineRenderer(hit, multipleStr, selectedValues, fields, modalId) {
    const nameField = fields.length > 1 ? fields[1] : 'name'
    const checked = selectedValues.includes(hit.id) ? 'checked' : ''
    const labelClass = 'hover:bg-gray-200 cursor-pointer w-full p-1 rounded flex gap-1 items-center justify-between'
    let line = ''
    line += `<div class="">`
    line += `<label class="${labelClass}">`
    line += `<div class="flex items-center">`
    if (multipleStr) {
        line += `<input ${checked} name="id" type="checkbox" class="mx-1" onchange="modalSearchableDialogOnSelect(${hit.id}, '${modalId}', '${multipleStr}', '${nameField}')">`
    } else {
        line += `<input ${checked} name="id" type="radio" class="mx-1" onchange="modalSearchableDialogOnSelect(${hit.id}, '${modalId}', '${multipleStr}', '${nameField}')">`
    }
    line += hit[nameField] || hit.id
    line += `</div>`
    line += `<span>`
    line += makeId(hit.id)
    line += `</span>`
    line += '</label>'
    line += `</div>`
    return line
}

function modalSearchableDialogInvoke(url, keyword, multipleStr, selectedValues, modalId) {
    // console.log('ModalSearchableDialog.js', url, keyword, modalId)
    $.ajax({
        url,
        type: 'GET',
        data: {
            keyword,
            selectingValues: selectedValues,
        },
        success: function (response) {
            const pageSize = 100
            const { hits, meta } = response
            const { countTotal, fields } = meta

            modalSearchableDialogHits = {}
            hits.forEach((hit) => (modalSearchableDialogHits[hit.id] = hit))

            const objs = []
            hits.forEach((hit) => objs.push(modalSearchableDialogLineRenderer(hit, multipleStr, selectedValues, fields, modalId)))
            if (countTotal - pageSize > 0) objs.push(`<div class="font-bold ml-5">and ${countTotal - pageSize} more ...</div>`)
            $(`#${modalId}_result`).html(objs.join(''))
        },
        error: function (data) {
            // console.log(data);
            toastr.error(data.responseJSON.message)
        },
    })
}
