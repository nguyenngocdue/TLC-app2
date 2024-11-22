import Sortable from 'sortablejs'
import { TableParams } from '../Type/EditableTable3ParamType'

export const applySortableRow = (params: TableParams) => {
    const { tableName } = params
    const tableBody = document.querySelector(`#${tableName} tbody`) as HTMLElement
    Sortable.create(tableBody, {
        animation: 150,
        handle: '.drag-handle', // Use the drag handle for sorting
        // filter: ':not(.draggable)', // Exclude rows that do not have the "draggable" class
        onEnd: (evt) => {
            console.log('Row moved:', evt.oldIndex, '->', evt.newIndex)
        },
    })
}
