import { getRenderer } from "../Renderers/Renderers"
import { setCurrentValue } from "./Controls"
import { getEById } from "../functions"

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
    let spanElement = "SPAN ELEMENT"

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
            $(inputElement).on('change', () => {
                const newValue = $(`#${controlId}`).val()
                if (debug) console.log(`onChange of ${controlId} (inputElement) New value = ${newValue}`)
                setCurrentValue(tableId, dataIndex, dataSourceIndex, newValue)

                newRenderer = getRenderer(column, dataSourceIndex, tableId)
                tdElement.innerHTML = newRenderer;
                destroySelect2(inputElement)
            })
            spanElement = tdElement.querySelector(`span[tabindex="0"]`)
            spanElement.addEventListener('blur', function (event) {
                if (event.relatedTarget === null || event.relatedTarget.tagName.toLowerCase() !== 'body') {
                    // const newValue = $(`#${controlId}`).val()
                    // setCurrentValue(tableId, dataIndex, dataSourceIndex, newValue)

                    newRenderer = getRenderer(column, dataSourceIndex, tableId)
                    tdElement.innerHTML = newRenderer;
                    destroySelect2(inputElement)
                }
            })
            return
        case 'dropdown_multi':
            $(inputElement).on('change', () => {
                const newValue = $(inputElement).val()

                // if (debug) 
                console.log(`onChange of ${controlId} (inputElement) New value = ${newValue}`)
                setCurrentValue(tableId, dataIndex, dataSourceIndex, newValue)

                newRenderer = getRenderer(column, dataSourceIndex, tableId)
                tdElement.innerHTML = newRenderer;
                destroySelect2(inputElement)
            })
            spanElement = tdElement.querySelector(`span input[tabindex="0"]`)
            if (spanElement) {
                spanElement.addEventListener('blur', function (event) {
                    if (event.relatedTarget === null || event.relatedTarget.tagName.toLowerCase() !== 'body') {
                        // const newValue = $(`#${controlId}`).val()
                        // setCurrentValue(tableId, dataIndex, dataSourceIndex, newValue)

                        newRenderer = getRenderer(column, dataSourceIndex, tableId)
                        tdElement.innerHTML = newRenderer;
                        // destroySelect2(inputElement)
                    }
                })
            }
            return
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}
