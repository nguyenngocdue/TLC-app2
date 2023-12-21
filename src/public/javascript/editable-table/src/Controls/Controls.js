
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

export const getInputElement = (column, tdElement) => {
    const { renderer } = column
    switch (renderer) {
        case 'toggle':
        case 'picker':
        case 'text':
            return tdElement.querySelector('input');
        case 'dropdown':
            return tdElement.querySelector('select');
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}

export const focusToControl = (inputElement, column, tdElement) => {
    const { renderer } = column
    switch (renderer) {
        case 'toggle':
        case 'picker':
        case 'text':
            inputElement.focus();
            return inputElement
        case 'dropdown':
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

export const postRenderControl = (inputElement, column) => {
    const { renderer } = column
    switch (renderer) {
        case 'toggle':
        case 'picker':
        case 'text':
            return
        case 'dropdown':
            $(inputElement).select2({})
            $(inputElement).on('select2:close', function (e) {
                // This function will be called when the Select2 dropdown is closed
                console.log('Select2 dropdown closed');
                // Add your logic here for actions after the Select2 dropdown closes
            });
            $(inputElement).blur(() => {
                console.log(111)
            })
            return
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}