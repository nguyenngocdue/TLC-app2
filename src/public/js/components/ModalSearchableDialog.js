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
        const currentValues = getEById(valueName).val()
        const currentValuesArray = currentValues ? currentValues.split(',') : []
        const index = currentValuesArray.indexOf(id.toString())
        if (index > -1) currentValuesArray.splice(index, 1)
        else currentValuesArray.push(id)

        result = currentValuesArray.join(',')
    } else {
        result = id.toString()
    }
    getEById(valueName).val(result)
    return result
}

let modalSearchableDialogMode = 'createNew'
let modalSearchableDialogNameField = null
function modalSearchableDialogOnSelectHandleText(modalId) {
    const newIdStrs = getEById(modalId + '_selectedValue').val()
    const valueName = modalId + '_selectedText'

    const newIdArray = newIdStrs ? newIdStrs.split(',') : []
    const newText = newIdArray.map((id) => renderTag(modalSearchableDialogHits[id][modalSearchableDialogNameField])).join('')
    // console.log('newIdArray', newIdArray, 'newText', newText)
    getEById(valueName).html(newText + '&nbsp')
}

let modalSearchableDialogHits = {}
function modalSearchableDialogOnSelect(id, modalId, multipleStr) {
    modalSearchableDialogOnSelectHandleValue(id, modalId, multipleStr)
    modalSearchableDialogOnSelectHandleText(modalId)
}

function modalSearchableDialogHeaderRenderer(fields, allowEdit) {
    const className = 'sticky top-0 bg-gray-200 font-bold p-1 text-center1'
    let header = ``
    header += `<div class="${className} table-row flex1 justify-around">`
    header += `<div class="table-cell border" style="width:40;">No.</div>`
    header += `<div class="table-cell" style="width:40px;"></div>`
    if (allowEdit) header += `<div class="table-cell" style="width:40px;">Edit</div>`
    header += fields.map((field) => `<div class="table-cell text-center p-1" style="width:${field.width}px;">${field.label}</div>`).join('')
    header += `</div>`

    return header
}

function modalSearchableDialogLineRenderer(hit, index, multipleStr, selectedValues, fields, modalId, allowEdit) {
    modalSearchableDialogNameField = fields.length > 0 ? fields[0].name : 'name'
    // console.log('Set nameField to', modalSearchableDialogNameField)
    if (typeof selectedValues == 'string') selectedValues = selectedValues.split(',').map((x) => parseInt(x))
    const checked = selectedValues.includes(hit.id) ? 'checked' : ''
    // console.log(selectedValues, hit.id, checked)
    const type = multipleStr ? 'checkbox' : 'radio'

    let line = ''
    line += `<label class="table-row flex1 w-full justify-around items-center hover:bg-gray-200 cursor-pointer">`

    line += `<div class="table-cell border text-center">${index + 1}</div>`
    line += `<div class="table-cell border text-center" style="width:40px;">`
    line += `<input ${checked} name="whatever" type="${type}" class="mx-1" onchange="modalSearchableDialogOnSelect(${hit.id}, '${modalId}', '${multipleStr}')">`
    line += `</div>`

    if (allowEdit) {
        line += `<div class="table-cell border text-center" style="width:40px;">`
        line += `<button onclick="event.stopPropagation();prepareForUpdate(${hit.id})" type=button class="hover:text-blue-600">`
        line += `<i class="fas fa-pencil"></i>`
        line += `</button>`
        line += `</div>`
    }

    line += fields
        .map((field) => {
            const value = field.name == 'id' ? makeId(hit[field.name]) : hit[field.name]
            return `<div class="truncate w-full border table-cell px-2" style="width:${field.width}px;">${value}</div>`
        })
        .join('')

    line += `</label>`
    return line
}

function modalSearchableDialogInvoke(url, keyword, multipleStr, selectedValues, modalId, allowEdit) {
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
            const { countTotal1, fields } = meta

            modalSearchableDialogHits = {}
            hits.forEach((hit) => (modalSearchableDialogHits[hit.id] = hit))

            const objs = []
            objs.push(modalSearchableDialogHeaderRenderer(fields, allowEdit))
            hits.forEach((hit, index) =>
                objs.push(modalSearchableDialogLineRenderer(hit, index, multipleStr, selectedValues, fields, modalId, allowEdit)),
            )

            let andMore1 = ''
            if (countTotal1 - hits.length > 0)
                andMore1 = `<div class="col-span-12 font-bold ml-5">and ${countTotal1 - hits.length} more ...</div>`
            getEById(`${modalId}_result`).html(objs.join('') + andMore1)
            $(`#divSearchResult`).html(hits.length + ' / ' + countTotal1 + ' items')

            //Update the selected Value Text Tag Box
            modalSearchableDialogOnSelectHandleText(modalId)
        },
        error: function (data) {
            // console.log(data);
            toastr.error(data.responseJSON.message)
        },
    })
}
