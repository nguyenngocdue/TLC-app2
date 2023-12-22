export const ETCDropdown = (controlParams) => {
    const { currentValue, column, controlId } = controlParams
    const { dataSource } = column
    // console.log(column, dataSource)
    const options = Object.keys(dataSource).map((id) => {
        const option = dataSource[id]['name']
        // console.log(option, id, cell)
        const selected = (id == currentValue) ? "selected" : ""
        return `<option value="${id}" ${selected}>${option}</option>`
    })
    // console.log(options)
    return `<select id="${controlId}" class="w-full">
        ${options.join('')}
    </select>`
    // return `<input type="text" class="w-full" value="${cell}" >`
}