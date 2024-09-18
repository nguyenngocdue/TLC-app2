$(document).ready(() => {
    $('#json_tree_1')
        .jstree({
            core: {
                data: jsonTree,
                check_callback: true, // Allow modifications
            },
            plugins: ['wholerow', 'checkbox'],
        })
        .on('changed.jstree', (e, data) => {
            // console.log(data.selected)
            for (let i = 0; i < data.selected.length; i++) {
                console.log(data.instance.get_node(data.selected[i]).data.type)
            }
        })
})
