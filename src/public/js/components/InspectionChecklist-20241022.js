const reRenderCheckpoint2 = (lineId, id, behaviorOf) => {
    $('#divSubOptionNCR_' + lineId).hide()
    // $("#divSubOptionOnHold_" + lineId).hide()
    const checked = $(`#radio_${lineId}_${id}`).prop('checked')
    // console.log('reRenderCheckpoint2', lineId, id, behaviorOf, checked)

    $('#divSubOptionNCR_' + lineId).hide()
    $('#divSubOptionOnHold_' + lineId).hide()

    switch (behaviorOf) {
        case 785: // Pass/Yes
            break
        case 786: // Fail/No
            if (checked) $('#divSubOptionNCR_' + lineId).show()
            break
        case 787: // N/A
            break
        case 788: // On Hold
            if (checked) $('#divSubOptionOnHold_' + lineId).show()
            break
        default:
            console.log('Unknown behaviorOf ', behaviorOf)
            break
    }
}

function uncheckOther2(idArray, lineId, id) {
    const array = idArray.slice() // shallow copy
    array.splice(array.indexOf(id), 1)
    // console.log(lineId, id, array)

    array.forEach((optionId) => {
        const id = 'radio_' + lineId + '_' + optionId
        $(`#${id}`).prop('checked', false)
    })
}

function uncheckMe2(lineId, id) {
    //This to trigger progress bar to rerender
    const checked = $(`#radio_${lineId}_${id}`).prop('checked')
    //Check the GHOST item
    const x = 'radio_' + lineId + '_' + 0
    $(`#${x}`).prop('checked', checked)
}

// function updateIdsOfFail(id, name) {
//     const currentValue = $('#' + name).val() ? '' : id
//     $('#' + name).val(currentValue)
// }

const updateInspectorId2 = (id, cuid) => {
    // console.log(id, name ,valueId,rowIndex,type)
    const inspector_id = 'inspector_id_' + id
    // console.log('Updating ', inspector_id, 'to', cuid)
    document.getElementById(inspector_id).value = cuid
}

function updateProgressBar2(table01Name) {
    // console.log("updateProgressBar", table01Name)
    const all = checkpoint_names[table01Name].length
    const values = {}
    checkpoint_names[table01Name].forEach((name) => {
        const key = `input[name='${name}']:checked`
        const val = $(key).val() || 0
        const valBehaviorId = $(key).attr('data-behavior-id') || 0
        if (values[valBehaviorId] === undefined) values[valBehaviorId] = 0
        values[valBehaviorId]++
    })
    const percent = {}
    Object.keys(values).map((key) => (percent[key] = Math.round((100 * values[key]) / all)))

    // console.log("values", values)
    // console.log("percent", percent)

    const keys = { 785: 'Pass', 786: 'Fail', 787: 'N/A', 788: 'On Hold' }
    Object.keys(keys).map((key) => {
        const control = $(`#progress_${key}`)
        if (control) control.html('').hide() //.css({ width: '-1%' })
    })

    Object.keys(percent).forEach((key) => {
        const val = percent[key]
        if (val) {
            $(`#progress_${key}`)
                .html(`<span class='w-full'>${keys[key]}<br/>${values[key]}/${all}</span>`)
                .css({ width: val + '%' })
                .show()
        } else {
            $(`#progress_${key}`).html('').hide()
        }
    })
}
