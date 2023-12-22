
import { ETCText } from "./ETCText"
import { ETCToggle } from "./ETCToggle"
import { ETCDropdown } from "./ETCDropdown"
import { ETCPicker } from "./ETCPicker"

import { getRenderer } from "../Renderers/Renderers"
import { getEById } from "../functions"

const debug = false

export const getCurrentValue = (tableId, dataIndex, dataSourceIndex) => {
    return editableTableValues[tableId]?.[dataSourceIndex]?.[dataIndex]
}

export const setCurrentValue = (tableId, dataIndex, dataSourceIndex, newValue) => {
    editableTableValues[tableId][dataSourceIndex][dataIndex] = newValue
}

export const getControl = (controlParams) => {
    const { column, cell } = controlParams
    const { renderer } = column
    switch (renderer) {
        case 'toggle':
        // return ETCToggle(controlParams)
        case 'picker':
        // return ETCPicker(controlParams)
        case 'text':
            return ETCText(controlParams)
        case 'dropdown':
            return ETCDropdown(controlParams)
        case 'dropdown_multi':
            return ETCDropdown(controlParams)
        case undefined:
            return cell
        default:
            return `Unknown control [${renderer}]`
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
        case 'dropdown_multi':
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
            return
        case 'dropdown':
        case 'dropdown_multi':
            $(inputElement).select2('open');
            return
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}

const destroySelect2 = (inputElement) => {
    var select2Instance = $(inputElement).data('select2');

    // Check if the Select2 instance exists before attempting to destroy it
    if (select2Instance !== undefined && select2Instance !== null) {
        // Call the destroy method on the instance
        select2Instance.destroy();
        // console.log("Destroyed.")
    }
}

export const attachControlEventHandler = (attachParams) => {
    const debug = true
    const { inputElement, column, tableId, controlId } = attachParams
    const { renderer, dataIndex } = column
    const tdElement = inputElement.parentNode
    const dataSourceIndex = tdElement.getAttribute("datasource-index")
    let newValue = `[?]`
    let newRenderer = "NEW RENDERER"

    switch (renderer) {
        case 'toggle':
        case 'picker':
        case 'text':
            inputElement.addEventListener('blur', function () {
                newValue = $(`#${controlId}`).val()
                if (debug) console.log(`onBlur of ${controlId} New value = ${newValue}`)
                setCurrentValue(tableId, dataIndex, dataSourceIndex, newValue)

                newRenderer = getRenderer(column, dataSourceIndex, tableId)
                tdElement.innerHTML = newRenderer;
            });
            return
        case 'dropdown':
        case 'dropdown_multi':
            $(inputElement).on('change', () => {
                const newValue = $(`#${controlId}`).val()
                if (debug) console.log(`onChange of ${controlId} (inputElement) New value = ${newValue}`)
                setCurrentValue(tableId, dataIndex, dataSourceIndex, newValue)

                newRenderer = getRenderer(column, dataSourceIndex, tableId)
                tdElement.innerHTML = newRenderer;
                destroySelect2(inputElement)
            })
            const spanElement = tdElement.querySelector(`span[tabindex="0"]`)
            spanElement.addEventListener('blur', function (event) {
                if (event.relatedTarget === null || event.relatedTarget.tagName.toLowerCase() !== 'body') {
                    const newValue = $(`#${controlId}`).val()
                    setCurrentValue(tableId, dataIndex, dataSourceIndex, newValue)

                    newRenderer = getRenderer(column, dataSourceIndex, tableId)
                    tdElement.innerHTML = newRenderer;
                    destroySelect2(inputElement)
                }
                // newValue = $(`#${controlId}`).val()
                // if (debug) console.log(`onBlur of ${controlId} New value = ${newValue}`)
                // newRenderer = getRenderer(column, newValue)
                // tdElement.innerHTML = newRenderer;
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
        case 'dropdown_multi':
            $(inputElement).select2({})
            return
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}