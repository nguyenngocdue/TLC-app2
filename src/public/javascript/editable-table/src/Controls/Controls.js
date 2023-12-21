
import { ETCText } from "./ETCText"
import { ETCToggle } from "./ETCToggle"
import { ETCDropdown } from "./ETCDropdown"
import { ETCPicker } from "./ETCPicker"

export const getCurrentValue = (column, tdElement) => {

    const { renderer } = column
    switch (renderer) {
        case 'toggle':
        // return ETCToggle(cell, column)
        case 'picker':
        // return ETCPicker(cell, column)
        case 'text':
            return tdElement.textContent;
        case 'dropdown':
            return tdElement.textContent;
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
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
            inputElement.focus();
            return inputElement
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}