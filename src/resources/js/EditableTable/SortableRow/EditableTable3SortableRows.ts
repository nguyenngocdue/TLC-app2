import Sortable from 'sortablejs'
import { TableParams } from '../Type/EditableTable3ParamType'
import { LengthAware } from '../Type/EditableTable3DataLineType'

declare let tableData: { [tableName: string]: LengthAware }

export const applySortableRow = (params: TableParams) => {
    const { tableName } = params
    const tableBody = document.querySelector(`#${tableName} tbody`) as HTMLElement
    Sortable.create(tableBody, {
        animation: 150,
        handle: '.drag-handle', // Use the drag handle for sorting
        // filter: ':not(.draggable)', // Exclude rows that do not have the "draggable" class
        onEnd: (evt) => {
            // console.log('Row moved:', evt.oldIndex, '->', evt.newIndex)
            if (evt.oldIndex === evt.newIndex) return
            if (evt.oldIndex === undefined) return
            if (evt.newIndex === undefined) return

            const dataSource = tableData[tableName]
            //Swap the data source
            const oldIndex = evt.oldIndex - 1
            const newIndex = evt.newIndex - 1
            const temp = dataSource.data[oldIndex]
            dataSource.data[oldIndex] = dataSource.data[newIndex]
            dataSource.data[newIndex] = temp
        },
    })
}
