
import { ETCText } from "./ETCText"
import { ETCToggle } from "./ETCToggle"
import { ETCDropdown } from "./ETCDropdown"
import { ETCPicker } from "./ETCPicker"

export const getCurrentValue = (tableId, dataIndex, dataSourceIndex) => {
    const dataSource = editableTableValues[tableId]
    // console.log(dataSource, tdElement, dataSourceIndex)
    return dataSource[dataSourceIndex][dataIndex]
}

export const setCurrentValue = (tableId, dataIndex, dataSourceIndex, newValue) => {
    const dataSource = editableTableValues[tableId]
    // console.log(dataSource, tdElement, dataSourceIndex)
    return dataSource[dataSourceIndex][dataIndex] = newValue
}

export const getControl = (column, cell) => {
    const { renderer } = column
    switch (renderer) {
        case 'toggle':
        // return ETCToggle(cell, column)
        case 'picker':
        // return ETCPicker(cell, column)
        case 'text':
            return ETCText(cell, column)
        case 'dropdown':
            return ETCDropdown(cell, column)
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}

export const focusToControl = (column, tdElement) => {
    const { renderer } = column
    let inputElement
    switch (renderer) {
        case 'toggle':
        case 'picker':
        case 'text':
            inputElement = tdElement.querySelector('input');
            inputElement.focus();
            return inputElement
        case 'dropdown':
            inputElement = tdElement.querySelector('select');
            // const event = new Event('mousedown', { bubbles: true, cancelable: true });
            // inputElement.dispatchEvent(event);
            // console.log("focus dropdown")
            inputElement.focus();
            return inputElement
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}