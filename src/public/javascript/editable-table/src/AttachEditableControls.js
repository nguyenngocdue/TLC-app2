import { focusToControl, getControl, getCurrentValue, setCurrentValue } from './Controls/Controls'
import { getRenderer } from './Renderers/Renderers'

export const myAddEventListener = (params, tableId) => {
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
        // console.log(column)
        // console.log(`makeEditableField currentValue: ${currentValue} ${dataIndex}`)

        // const currentValue = tdElement.textContent;
        const currentValue = getCurrentValue(tableId, dataIndex, dataSourceIndex);
        // console.log(currentValue)
        tdElement.innerHTML = getControl(column, currentValue)

        const inputElement = focusToControl(column, tdElement)

        inputElement.addEventListener('blur', function () {
            // console.log(this, this.value)
            const newValue = this.value;
            const renderer = getRenderer(column, newValue)
            tdElement.innerHTML = renderer;

            setCurrentValue(tableId, dataIndex, dataSourceIndex, newValue)
        });
    }

    function rewindFocus() {
        let index = Array.from(editableCells).indexOf(this);
        const previousIndex = (index - 1 + editableCells.length) % editableCells.length
        const target = editableCells[previousIndex]
        target.focus()
    }
}