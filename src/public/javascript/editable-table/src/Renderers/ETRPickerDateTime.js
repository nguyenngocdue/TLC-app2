export const ETRPickerDateTime = (cell, column) => {
    const content = moment(cell).format("DD/MM/YYYY HH:mm")
    return `${content}`
}