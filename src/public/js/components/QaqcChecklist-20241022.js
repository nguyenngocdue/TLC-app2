const reRenderCheckpoint = (lineId, id) => {
    $('#divSubOptionNCR_' + lineId).hide()
    // $("#divSubOptionOnHold_" + lineId).hide()
    const checked = $(`#radio_${lineId}_${id}`).prop('checked')
    // console.log(checked)
    switch (id) {
        case 2:
        case 6:
            if (checked) $('#divSubOptionNCR_' + lineId).show()
            else $('#divSubOptionNCR_' + lineId).hide()
            break
        // case 4:
        // case 8: if(checked) $("#divSubOptionOnHold_" + lineId).toggle(); else $("#divSubOptionOnHold_" + lineId).hide(); break;
        // default: console.log("Unknown option "+id)
    }
}

function uncheckOther(idArray, lineId, id) {
    const array = idArray.slice() // shallow copy
    array.splice(array.indexOf(id), 1)
    // console.log(lineId, id, array)

    array.forEach((optionId) => {
        const id = 'radio_' + lineId + '_' + optionId
        $(`#${id}`).prop('checked', false)
    })
}

function uncheckMe(lineId, id) {
    const checked = $(`#radio_${lineId}_${id}`).prop('checked')
    // console.log("Un-checked me", id, checked)
    const x = 'radio_' + lineId + '_' + 0
    $(`#${x}`).prop('checked', checked)
}

var objIds = {}
function updateIdsOfFail(id, name, valueId, rowIndex, type) {
    //showOrHiddenGroupAttachmentAndComment(valueId,rowIndex,type)
    if (!Object.keys(objIds).includes(name)) {
        objIds[name] = []
        if ([2, 6].includes(valueId)) {
            objIds[name].push(id)
        }
    } else {
        if (objIds[name].includes(id)) {
            const index = objIds[name].indexOf(id)
            objIds[name].splice(index, 1)
        } else {
            if ([2, 6].includes(valueId)) {
                objIds[name].push(id)
            }
        }
    }
    document.getElementById(name).value = objIds[name]
}

const updateInspectorId = (id, cuid) => {
    // console.log(id, name ,valueId,rowIndex,type)
    const inspector_id = 'radio_inspector_id_' + id
    document.getElementById(inspector_id).value = cuid
}

function updateProgressBar(table01Name) {
    // console.log("updateProgressBar", table01Name)
    // $("input[name='table01[hse_insp_control_value_id][4135]']:checked").val();
    const all = checkpoint_names[table01Name].length
    const values = {}
    checkpoint_names[table01Name].forEach((name) => {
        const val = $("input[name='" + name + "']:checked").val()
        if (values[val] === undefined) values[val] = 0
        values[val]++
    })
    // const percent = Object.keys(values).map((key) => Math.round( 100 * values[key] / all))
    const percent = {}
    Object.keys(values).map((key) => (percent[key] = Math.round((100 * values[key]) / all)))

    // console.log(values, percent)
    if (percent[1]) {
        $('#progress_yes')
            .html(`<span class='w-full'>Pass<br/>${values[1]}/${all}</span>`)
            .css({ width: percent[1] + '%' })
            .show()
    } else {
        $('#progress_yes').html('').hide()
    }
    if (percent[2]) {
        $('#progress_no')
            .html(`<span class='w-full'>Fail<br/>${values[2]}/${all}</span>`)
            .css({ width: percent[2] + '%' })
            .show()
    } else {
        $('#progress_no').html('').hide()
    }
    if (percent[3]) {
        $('#progress_na')
            .html(`<span class='w-full'>NA<br/>${values[3]}/${all}</span>`)
            .css({ width: percent[3] + '%' })
            .show()
    } else {
        $('#progress_na').html('').hide()
    }
    if (percent[4]) {
        $('#progress_on_hold')
            .html(`<span class='w-full'>On Hold<br/>${values[4]}/${all}</span>`)
            .css({ width: percent[4] + '%' })
            .show()
    } else {
        $('#progress_on_hold').html('').hide()
    }

    // $("#progress_no").css({width: (percent[2] ? percent[2] : 0 ) +'%'})
    // $("#progress_na").css({width: (percent[3] ? percent[3] : 0 )+'%'})
    // $("#progress_on_hold").css({width: (percent[4] ? percent[4] : 0 )+'%'})
}
