export const ETRPickerTime = (cell, column) => {
    const content = moment('2000-01-01 ' + cell).format("HH:mm")
    return `${content}`
}