export const ETRPickerDate = (cell, column) => {
    const content = moment(cell).format("DD/MM/YYYY")
    return `${content}`
}