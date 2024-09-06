function renderInputField(name, value) {
    return `<input name="${name}" type="hidden" class="border rounded p-1" readonly1 value="${value}" tabindex="-1" />`
}

function renderTag(value) {
    const className =
        'inline-block bg-gray-200 text-gray-800 dark:bg-gray-800 dark:text-gray-200  rounded whitespace-nowrap font-semibold text-xs-vw text-xs m-0.5 px-2 py-1'
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
        result = id.toString()
    }
    $('#' + valueName).val(result)
    return result
}

function modalSearchableDialogOnSelectHandleText(id, modalId, newIdStrs, nameField) {
    const valueName = modalId + '_selectedText'

    const newIdArray = newIdStrs ? newIdStrs.split(',') : []
    const newText = newIdArray.map((id) => renderTag(modalSearchableDialogHits[id][nameField])).join('')
    $('#' + valueName).html(newText + '&nbsp')
}

let modalSearchableDialogHits = {}
function modalSearchableDialogOnSelect(id, modalId, multipleStr, nameField) {
    const newIdStrs = modalSearchableDialogOnSelectHandleValue(id, modalId, multipleStr)
    modalSearchableDialogOnSelectHandleText(id, modalId, newIdStrs, nameField)
}

function modalSearchableDialogHeaderRenderer(hits, multipleStr, selectedValues, fields, modalId) {
    console.log(fields)
    const className = 'sticky top-0 bg-gray-200 font-bold p-1 text-center1'
    let header = ``
    header += `<div class="${className} table-row flex1 justify-around">`
    header += `<div class="table-cell border" style="width:40;">No.</div>`
    header += `<div class="table-cell" style="width:100px;"></div>`
    header += fields.map((field) => `<div class="table-cell text-center p-1" style="width:${field.width}px;">${field.label}</div>`).join('')
    header += `</div>`

    return header
}

function modalSearchableDialogLineRenderer(hit, index, multipleStr, selectedValues, fields, modalId) {
    const nameField = fields.length > 0 ? fields[0].name : 'name'
    if (typeof selectedValues == 'string') selectedValues = selectedValues.split(',').map((x) => parseInt(x))
    const checked = selectedValues.includes(hit.id) ? 'checked' : ''
    // console.log(selectedValues, hit.id, checked)
    const type = multipleStr ? 'checkbox' : 'radio'

    let line = ''
    line += `<label class="table-row flex1 w-full justify-around items-center hover:bg-gray-200 cursor-pointer">`

    line += `<div class="table-cell border">${index + 1}</div>`
    line += `<div class="table-cell border" style="width:100px;">`
    line += `<input ${checked} name="whatever" type="${type}" class="mx-1" onchange="modalSearchableDialogOnSelect(${hit.id}, '${modalId}', '${multipleStr}', '${nameField}')">`
    line += `</div>`

    line += fields
        .map((field) => {
            const value = field.name == 'id' ? makeId(hit[field.name]) : hit[field.name]
            return `<div class="truncate w-full border table-cell px-2" style="width:${field.width}px;">${value}</div>`
        })
        .join('')

    line += `</label>`
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
            const { hits, meta } = response
            const { countTotal, fields } = meta

            modalSearchableDialogHits = {}
            hits.forEach((hit) => (modalSearchableDialogHits[hit.id] = hit))

            const objs = []
            objs.push(modalSearchableDialogHeaderRenderer(hits, multipleStr, selectedValues, fields, modalId))

            hits.forEach((hit, index) =>
                objs.push(modalSearchableDialogLineRenderer(hit, index, multipleStr, selectedValues, fields, modalId)),
            )

            let andMore = ''
            if (countTotal - hits.length > 0)
                andMore = `<div class="col-span-12 font-bold ml-5">and ${countTotal - hits.length} more ...</div>`
            $(`#${modalId}_result`).html(objs.join('') + andMore)
            $(`#divSearchResult`).html(hits.length + ' / ' + countTotal + ' items')
        },
        error: function (data) {
            // console.log(data);
            toastr.error(data.responseJSON.message)
        },
    })
}
