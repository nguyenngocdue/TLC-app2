
import { ETCText } from "./ETCText"
import { ETCToggle } from "./ETCToggle"
import { ETCDropdown } from "./ETCDropdown"
import { ETCDropdownMulti } from "./ETCDropdownMulti"
import { ETCPicker } from "./ETCPicker"
import { ETCPickerDate } from "./ETCPickerDate"
import { ETCPickerTime } from "./ETCPickerTime"

export const getCurrentValue = (tableId, dataIndex, dataSourceIndex) => {
    return editableTableValues[tableId]?.[dataSourceIndex]?.[dataIndex]
}

export const setCurrentValue = (tableId, dataIndex, dataSourceIndex, newValue) => {
    editableTableValues[tableId][dataSourceIndex][dataIndex] = newValue
    // console.log("SETTING", tableId, dataIndex, dataSourceIndex, newValue)
    // console.log("GETTING", editableTableValues[tableId][dataSourceIndex][dataIndex])
}

export const getControl = (controlParams) => {
    const { column, currentValue } = controlParams
    const { control } = column

    switch (control) {
        case 'picker':
        case 'text':
            return ETCText(controlParams)
        case 'dropdown':
            return ETCDropdown(controlParams)
        case 'dropdown_multi':
            return ETCDropdownMulti(controlParams)
        case 'toggle':
            return ETCToggle(controlParams)
        case 'picker_date':
            return ETCPickerDate(controlParams)
        case 'picker_time':
            return ETCPickerTime(controlParams)
        case undefined:
            return currentValue
        default:
            return `Unknown control [${control}]`
    }
}

export const getInputElement = (column, tdElement) => {
    const { control } = column
    switch (control) {
        case 'toggle':
        case 'text':
            return tdElement.querySelector('input');
        case 'picker_date':
        case 'picker_time':
            return tdElement.querySelector('input');
        case 'dropdown':
        case 'dropdown_multi':
            return tdElement.querySelector('select');
        case undefined:
            return cell
        case 'picker':
        default:
            return `Unknown control [${control}]`
    }
}

export const focusToControl = (inputElement, column) => {
    const { control } = column
    switch (control) {
        case 'picker':
            console.log(inputElement, control)
            return
        case 'toggle':
        case 'text':
            inputElement.focus();
            return
        case 'dropdown':
        case 'dropdown_multi':
            $(inputElement).select2('open');
            return
        case 'picker_date':
            const flatpickrInstance = $(inputElement)[0]._flatpickr
            flatpickrInstance.open()
            return
        case 'picker_time':
            return 'not yet implemented'
        case undefined:
            return cell
        default:
            return `Unknown control [${control}]`
    }
}

export const postRenderControl = (inputElement, column) => {
    const { control, dataIndex } = column
    const tdElement = inputElement.parentNode
    switch (control) {
        case 'picker_date':
        case 'picker_time':
            return flatpickr(inputElement, {
                // enableTime: true,
                altInput: true,
                altFormat: "d/m/Y",
                dateFormat: 'Y-m-d',
                weekNumbers: true,
                time_24hr: true,
                // onClose: () => console.log("CLOSED")
            });
            return
        case 'toggle':
        case 'picker':
        case 'text':
            return
        case 'dropdown':
        case 'dropdown_multi':
            $(inputElement).select2({})
            return
        case undefined:
            return cell
        default:
            return `Unknown control [${control}]`
    }
}