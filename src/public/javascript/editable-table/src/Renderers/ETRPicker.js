export const ETRPicker = (cell, column) => {
    // const content = column.dataSource[cell] || '??'
    const content = moment(cell).format("DD/MM/YYYY")
    const value = `<input tabindex="-1" type="text" value="${cell}" >`
    return `${content} ${value}`
}