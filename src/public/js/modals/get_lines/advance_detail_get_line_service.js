$(document).ready(() => {
    const renderTree = (jsonTree) => {
        $('#json_tree_1')
            .jstree({
                core: { data: jsonTree },
                plugins: ['wholerow', 'checkbox'],
            })
            .on('changed.jstree', (e, selectedNode) => {
                if (!!inputId) {
                    const result = []
                    for (let i = 0; i < selectedNode.selected.length; i++) {
                        const node = selectedNode.instance.get_node(selectedNode.selected[i])
                        const { id, data } = node
                        const { type, act_adv_req_id } = data
                        // console.log('Current node:', id, type)
                        if (['adv_line'].includes(type)) {
                            result.push(act_adv_req_id)
                        }
                        // console.log('Current all status:', data.instance.get_node(data.selected[i]).id)
                    }
                    // console.log('All selected:', result.join())
                    $('#' + inputId).val(result.join())
                }
            })
    }
    const ajaxLoadData = (url) => {
        userId = $('#user_id').val()
        console.log('userId', userId)
        url = url + '?user_id=' + userId
        $.ajax({
            url,
            success: (data) => {
                $('#divModalDataLoading').hide()
                jsonTree = data
                renderTree(data)
            },
            error: (error) => toastr.error('Error: ' + error.responseJSON.message),
        })
    }

    ajaxLoadData('/api/v1/custom_modal/advance_detail_get_line_service')
    console.log('advance_detail_get_line_service.js')
})
