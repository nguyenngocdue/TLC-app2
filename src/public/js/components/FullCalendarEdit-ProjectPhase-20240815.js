function hideTaskList() {
    $('#sidebar_tasklist_container').hide()
    $('#sidebar_tasklist_container_warning').show()
}
function showTaskList() {
    $('#sidebar_tasklist_container').show()
    $('#sidebar_tasklist_container_warning').hide()
}
function checkIfAnyFilterIsNull() {
    // console.log('checkIfAnyFilterIsNull');

    const a = $('#project_id_11111').val()
    const b = $('#sub_project_id_11111').val()
    const c = $('#lod_id_11111').val()
    const d = $('#work_mode_id_11111').val()
    const enabled = !a || !b || !c || !d
    if (enabled) {
        hideTaskList()
        // console.log('disable');
    } else {
        showTaskList()
        // console.log('enable');
    }
}
var toBeKept = []
function removeUnrelatedPhase(newProjectId) {
    const allPhaseIds = k['pj_task_phases'].map((phase) => phase.id)
    // console.log('allPhaseIds', allPhaseIds)
    toBeKept = []
    const overheadIds = [
        1, //OverHead
        2, //NZ Overhead
        12, //SG Overhead
    ]
    switch (newProjectId * 1) {
        case 27: //HOF
        case 6: //TF1
        case 10: //TF2
        case 7: //TF3
            toBeKept = [1]
            break
        case 59: //NZO
            toBeKept = [2]
            break
        default:
            toBeKept = allPhaseIds.filter((phaseId) => overheadIds.includes(phaseId) == false)
            break
    }
    // console.log('newProjectId', newProjectId, toBeKept)
}
$('#lod_id_11111').on('select2:open', function () {
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
$('#project_id_11111').change(() => {
    checkIfAnyFilterIsNull()
    removeUnrelatedPhase($('#project_id_11111').val())
})

$('#sub_project_id_11111').change(() => checkIfAnyFilterIsNull())
$('#lod_id_11111').change(() => checkIfAnyFilterIsNull())
$('#work_mode_id_11111').change(() => checkIfAnyFilterIsNull())

checkIfAnyFilterIsNull()
