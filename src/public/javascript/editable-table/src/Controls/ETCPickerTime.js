export const ETCPickerTime = (cell, column) => {
    const { currentValue } = cell
    return `<input component="ETCPickerTime" type="text" class="w-full" value="${currentValue}" >`
}