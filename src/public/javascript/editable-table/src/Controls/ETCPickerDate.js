
export const ETCPickerDate = (cell, column) => {
    // console.log(cell)
    const { currentValue } = cell
    return `<input component="ETCPickerDate" type="text" class="w-full" value="${currentValue}" >`
}