export const ETCPickerDateTime = (cell, column) => {
    const { currentValue } = cell
    return `<input component="ETCPickerDateTime" type="text" class="w-full" value="${currentValue}" >`
}