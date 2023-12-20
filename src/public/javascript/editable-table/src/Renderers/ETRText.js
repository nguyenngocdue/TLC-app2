
export const ETRText = (cell, column) => {
    const content = cell
    const value = `<input tabindex="-1" type="text" value="${cell}" >`
    return `<span class="truncate">${content}</span>${value}`
}