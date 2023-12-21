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
    editableCells.forEach(cell => {
        cell.addEventListener('keydown', function (event) {
            if (event.key === 'Tab' && !event.shiftKey) {
                makeEditable.call(this);
            } else if (event.key === 'Tab' && event.shiftKey) {
                rewindFocus.call(this);
            }
        });
        cell.addEventListener('focus', makeEditable);
        // cell.addEventListener('click', makeEditable);
    });

    function makeEditable() {
        const currentValue = this.textContent;
        console.log('makeEditable currentValue', currentValue)
        this.innerHTML = `<input type="text" value="${currentValue}" />`;

        const inputElement = this.querySelector('input');
        inputElement.focus();

        inputElement.addEventListener('blur', function () {
            const newValue = this.value;
            this.parentNode.innerHTML = newValue;
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