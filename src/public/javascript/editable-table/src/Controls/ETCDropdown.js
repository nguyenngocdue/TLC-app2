export const ETCDropdown = (cell, column) => {

    const { dataSource } = column
    // console.log(column, dataSource)
    const options = Object.keys(dataSource).map((id) => {
        const option = dataSource[id]['name']
        // console.log(option, id, cell)
        const selected = (id == cell) ? "selected" : ""
        return `<option value="${id}" ${selected}>${option}</option>`
    })
    // console.log(options)
    return `<select class="w-full">
        ${options.join('')}
    </select>`
    // return `<input type="text" class="w-full" value="${cell}" >`
}