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
                        const { type } = data
                        // console.log('Current node:', id, type)
                        if (['block'].includes(type)) {
                            result.push(id.substring('block_id_'.length))
                        }
                        // console.log('Current all status:', data.instance.get_node(data.selected[i]).id)
                    }
                    // console.log('All selected:', result.join())
                    $('#' + inputId).val(result.join())
                }
            })
    }

    const ajaxLoadData = (url) => {
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

    ajaxLoadData('/api/v1/custom_modal/block_detail_get_line_service')
    console.log('block_detail_get_line_service.js')
})
