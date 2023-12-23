export const ETCPickerDate = (cell, column) => {
    const { currentValue } = cell
    return `<input component="ETCPickerDate" type="text" class="w-full" value="${currentValue}" >`
}