import { focusToControl, getControl, } from './Controls/Controls'
import { getCurrentValue, getInputElement, postRenderControl, } from './Controls/Controls'
import { attachControlEventHandler } from './Controls/ControlEventHandlers'

export const AddEventListenerForEditableControls = (params, tableId) => {
    const editableCells = document.querySelectorAll(`.editable-cell-${tableId}:not(.hidden)`);
    // console.log(editableCells)
    const { columns } = params
    // console.log(columns)

    editableCells.forEach(cell => {
        cell.addEventListener('keydown', function (event) {
            if (event.key === 'Tab' && !event.shiftKey) {
                // makeEditableField.call(this);
            } else if (event.key === 'Tab' && event.shiftKey) {
                rewindFocus.call(this);
            }
        });
        cell.addEventListener('focus', makeEditableField);
        // cell.addEventListener('mouseover', makeEditableField);
        // cell.addEventListener('click', makeEditableField);
    });

    function makeEditableField() {

        const tdElement = this
        const dataIndex = tdElement.getAttribute("data-index")
        const dataSourceIndex = tdElement.getAttribute("datasource-index")
        const column = columns[dataIndex]
        const controlId = `${tableId}_${dataIndex}_${dataSourceIndex}`
        // console.log(column)
        // console.log(`makeEditableField currentValue: ${currentValue} ${dataIndex}`)

        // const currentValue = tdElement.textContent;
        const currentValue = getCurrentValue(tableId, dataIndex, dataSourceIndex);
        // console.log(controlId, currentValue)
        tdElement.innerHTML = getControl({ column, currentValue, dataSourceIndex, tableId, controlId })

        const inputElement = getInputElement(column, tdElement)
        postRenderControl(inputElement, column)
        focusToControl(inputElement, column)
        attachControlEventHandler({ inputElement, column, tableId, controlId })
    }

    const rewindFocus = () => {
        let index = Array.from(editableCells).indexOf(this);
        const previousIndex = (index - 1 + editableCells.length) % editableCells.length
        const target = editableCells[previousIndex]
        target.focus()
    }
}