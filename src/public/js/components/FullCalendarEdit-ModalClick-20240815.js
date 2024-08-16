$('#lod_id').on('select2:open', function () {
    const options = $(this).find('option')
    // console.log(toBeKept, options)
    options.each((index, option) => {
        const value = $(option).val()
        if (!toBeKept.includes(parseInt(value))) {
            // console.log('hide', value, option)
            $(option).prop('disabled', true)
        } else {
            // console.log('show', value, option)
            $(option).prop('disabled', false)
        }
    })

    // Refresh Select2 to reflect the changes
    $(this).trigger('change.select2')
})

function checkIfAnyFilterIsNull() {
    const a = $('#project_id').val()
    const b = $('#sub_project_id').val()
    const c = $('#lod_id').val()
    const d = $('#discipline_id').val()
    const e = $('#task_id').val()
    const f = $('#sub_task_id').val()
    const g = $('#work_mode_id').val()

    const disabled = !a || !b || !c || !d || !e || !f || !g
    // console.log("disabled",disabled, a,b,c,d,e,f,g)
    if (disabled) {
        $('#btnSaveModalClick').prop('disabled', true)
    } else {
        $('#btnSaveModalClick').prop('disabled', false)
    }
}

$('#project_id').change(() => {
    checkIfAnyFilterIsNull()
    removeUnrelatedPhase($('#project_id').val())
})
$('#sub_project_id').change(() => checkIfAnyFilterIsNull())
$('#lod_id').change(() => checkIfAnyFilterIsNull())
$('#discipline_id').change(() => checkIfAnyFilterIsNull())
$('#task_id').change(() => checkIfAnyFilterIsNull())
$('#sub_task_id').change(() => checkIfAnyFilterIsNull())
$('#work_mode_id').change(() => checkIfAnyFilterIsNull())

checkIfAnyFilterIsNull()
