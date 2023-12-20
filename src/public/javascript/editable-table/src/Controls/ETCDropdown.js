export const ETDropdown = (cell, column) => {
    console.log(cell, column,)
    const content = column.dataSource[cell] || '??'
    const value = `<input type="text" value="${cell}" >`
    return `${content} ${value}`
}