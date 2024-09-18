$(document).ready(() => {
    $('#json_tree_1')
        .jstree({ core: { data: jsonTree }, plugins: ['wholerow', 'checkbox'] })
        .on('changed.jstree', (e, data) => {
            if (!!inputId) {
                const result = []
                for (let i = 0; i < data.selected.length; i++) {
                    const id = data.instance.get_node(data.selected[i]).id
                    result.push(id)
                    console.log('Current all status:', data.instance.get_node(data.selected[i]).id)
                }
                console.log('All selected:', result.join())
                $('#' + inputId).val(result.join())
            }
        })
        .on('select_node.jstree', (e, data) => {
            console.log('Checked: ', data.instance.get_node(data.node).id)
        })
        .on('deselect_node.jstree', (e, data) => {
            console.log('Un-Checked: ', data.instance.get_node(data.node).id)
        })
    console.log('parentId7Tree.js')
})
