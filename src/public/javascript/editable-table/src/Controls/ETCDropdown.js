export const ETCDropdown = (cell, column) => {

    const { dataSourceIndexed } = column
    // console.log(column, dataSourceIndexed)
    const options = Object.keys(dataSourceIndexed).map((id) => {
        const option = dataSourceIndexed[id]['name']
        // console.log(option)
        return `<option value="${id}">${option}</option>`
    })
    console.log(options)
    return `<select>
        ${options.join('')}
    </select>`
    return `<input type="text" class="w-full" value="${cell}" >`
}