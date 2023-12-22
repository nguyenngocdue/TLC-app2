
import { ETCText } from "./ETCText"
import { ETCToggle } from "./ETCToggle"
import { ETCDropdown } from "./ETCDropdown"
import { ETCDropdownMulti } from "./ETCDropdownMulti"
import { ETCPicker } from "./ETCPicker"

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
        case 'toggle':
            return ETCToggle(controlParams)
        case 'picker':
        // return ETCPicker(controlParams)
        case 'text':
            return ETCText(controlParams)
        case 'dropdown':
            return ETCDropdown(controlParams)
        case 'dropdown_multi':
            return ETCDropdownMulti(controlParams)
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
        case 'toggle':
        case 'picker':
        case 'text':
            inputElement.focus();
            return
        case 'dropdown':
        case 'dropdown_multi':
            $(inputElement).select2('open');
            return
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