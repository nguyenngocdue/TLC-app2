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
                        if (['user'].includes(type)) {
                            // console.log('User:', id)
                            result.push(id.substring('user_'.length))
                        }
                        // console.log('Current all status:', data.instance.get_node(data.selected[i]).id)
                    }
                    // console.log('All selected:', result.join())
                    $('#' + inputId).val(result.join())
                }
            })
            .on('select_node.jstree', (e, data) => {
                console.log('Checked: ', data.instance.get_node(data.node).id)
            })
            .on('deselect_node.jstree', (e, data) => {
                console.log('Un-Checked: ', data.instance.get_node(data.node).id)
            })
    }
    const ajaxLoadData = (url) => {
        $.ajax({
            url,
            success: (data) => {
                $('#divModalDataLoading').hide()
                jsonTree = data
                if (jsonTree.length == 0) {
                    $('#divModalDataLoading').html('No data found').show()
                } else {
                    renderTree(data)
                }
            },
            error: (error) => toastr.error('Error: ' + error.responseJSON.message),
        })
    }

    ajaxLoadData('/api/v1/custom_modal/user_get_line_service')
    console.log('user_get_line_service.js')
})
