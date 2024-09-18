$(document).ready(() => {
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
                    const { type, finger_print } = data
                    // console.log('Current node:', id, type)
                    if (['travel_line'].includes(type)) {
                        // result.push(finger_print.substring('travel_line_'.length))
                        result.push(finger_print)
                    }
                    // console.log('Current all status:', data.instance.get_node(data.selected[i]).id)
                }
                // console.log('All selected:', result.join())
                $('#' + inputId).val(result.join())
            }
        })
    console.log('parentId7TreeFinExpenseTravelLines.js')
})
