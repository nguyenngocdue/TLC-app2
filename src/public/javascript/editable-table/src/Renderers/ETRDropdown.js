export const ETRDropdown = (cell, column) => {
    const content = column.dataSource[cell] || '??'
    const value = `<input tabindex="-1" type="text" value="${cell}" >`
    return `${content} ${value}`
}