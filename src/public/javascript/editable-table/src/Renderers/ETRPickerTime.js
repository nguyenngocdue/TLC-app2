export const ETRPickerTime = (cell, column) => {
    const content = moment(cell).format("DD/MM/YYYY")
    return `${content}`
    // const value = `<input tabindex="-1" type="text" value="${cell}" >`
    // return `${content} ${value}`
}