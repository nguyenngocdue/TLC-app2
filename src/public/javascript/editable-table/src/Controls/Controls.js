
import { ETCText } from "./ETCText"
import { ETCToggle } from "./ETCToggle"
import { ETCDropdown } from "./ETCDropdown"
import { ETCPicker } from "./ETCPicker"

import { getRenderer } from "../Renderers/Renderers"

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

export const focusToControl = (inputElement, column) => {
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

export const attachOnBlurHandler = (inputElement, column, tableId) => {
    const { renderer, dataIndex } = column
    const tdElement = inputElement.parentNode
    const dataSourceIndex = tdElement.getAttribute("datasource-index")
    const newValue = editableTableValues[tableId][dataSourceIndex][dataIndex]
    // console.log(value)

    switch (renderer) {
        case 'toggle':
        case 'picker':
        case 'text':
            inputElement.addEventListener('blur', function () {
                // const newValue = tdElement.innerHTML;
                // console.log(tdElement, newValue)
                const renderer = getRenderer(column, newValue)
                console.log("onBlur of", renderer)
                tdElement.innerHTML = renderer;

                // setCurrentValue(tableId, dataIndex, dataSourceIndex, newValue)
            });
            return
        case 'dropdown':
            const spanElement = tdElement.querySelector(`span[tabindex="0"]`)
            spanElement.addEventListener('blur', function (event) {
                console.log('onBlur of', renderer);
            });
            return
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}

export const postRenderControl = (inputElement, column) => {
    const { renderer, dataIndex } = column
    const tdElement = inputElement.parentNode
    switch (renderer) {
        case 'toggle':
        case 'picker':
        case 'text':
            return
        case 'dropdown':
            $(inputElement).select2({})
            return
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}