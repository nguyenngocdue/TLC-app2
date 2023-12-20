export const ETRToggle = (cell, column) => {
    const value = `<input tabindex="-1" type="text" value="${cell}" >`
    return `${cell}${value}`
}