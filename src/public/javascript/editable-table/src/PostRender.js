import { focusToControl, getControl, getCurrentValue } from './Controls/Controls'
import { getRenderer } from './Renderers/Renderers'
import { applyFixedColumnWidth } from './FrozenColumn'

const applyFixedColumns = (params, tableId) => {
    const { columns } = params
    applyFixedColumnWidth(tableId, columns)
}

const exposeParamsToWindow = (params, tableId) => {
    if (!window.editableTables) window.editableTables = {}
    window.editableTables[tableId] = params
}

const myAddEventListener = (params, tableId) => {
    const editableCells = document.querySelectorAll(`.editable-cell-${tableId}:not(.hidden)`);
    // console.log(editableCells)
    const { columnsIndexed } = params
    // console.log(columnsIndexed)

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
        const dataIndex = tdElement.getAttribute('dataIndex')
        const column = columnsIndexed[dataIndex]
        // console.log(column)
        // console.log(`makeEditableField currentValue: ${currentValue} ${dataIndex}`)

        // const currentValue = tdElement.textContent;
        const currentValue = getCurrentValue(column, tdElement);
        const control = getControl(column, currentValue)
        tdElement.innerHTML = control

        const inputElement = focusToControl(column, tdElement)

        inputElement.addEventListener('blur', function () {
            const newValue = this.value;
            const renderer = getRenderer(column, newValue)
            tdElement.innerHTML = renderer;
        });
    }

    function rewindFocus() {
        let index = Array.from(editableCells).indexOf(this);
        const previousIndex = (index - 1 + editableCells.length) % editableCells.length
        const target = editableCells[previousIndex]
        target.focus()
    }
}

export const postRender = (params) => {
    $(document).ready(() => {
        const { tableId } = params.tableParams
        applyFixedColumns(params, tableId)
        exposeParamsToWindow(params, tableId)
        myAddEventListener(params, tableId)
    })
}