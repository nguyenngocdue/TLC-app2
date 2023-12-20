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
    const editableCells = document.querySelectorAll(`.editable-cell-${tableId}`);
    // console.log(editableCells)
    editableCells.forEach(cell => {
        cell.addEventListener('keydown', function (event) {
            if (event.key === 'Tab' && !event.shiftKey) {
                console.log('ggggg')
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
        console.log('makeEditable', currentValue)
        this.innerHTML = `<input type="text" value="${currentValue}" />`;

        const inputElement = this.querySelector('input');
        inputElement.focus();

        inputElement.addEventListener('blur', function () {
            const newValue = this.value;
            this.parentNode.innerHTML = newValue;
        });
    }

    function rewindFocus() {
        console.log('rewindFocus', editableCells)
        let index = Array.from(editableCells).indexOf(this);
        if (index > 0) {
            editableCells[index - 1].focus();
        } else {
            editableCells[editableCells.length - 1].focus();
        }
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