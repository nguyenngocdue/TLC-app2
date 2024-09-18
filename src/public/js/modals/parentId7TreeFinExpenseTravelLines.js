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
                    const { type, diginet_business_trip_line_finger_print } = data
                    // console.log('Current node:', id, type)
                    if (['travel_line'].includes(type)) {
                        result.push(diginet_business_trip_line_finger_print)
                    }
                    // console.log('Current all status:', data.instance.get_node(data.selected[i]).id)
                }
                // console.log('All selected:', result.join())
                $('#' + inputId).val(result.join())
            }
        })
    console.log('parentId7TreeFinExpenseTravelLines.js')
})
