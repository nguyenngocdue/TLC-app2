import Sortable from 'sortablejs'
import { TableParams } from '../Type/EditableTable3ParamType'
import { LengthAware } from '../Type/EditableTable3DataLineType'
import {
    lastRenderedStartIdx,
    renderOneEmptyRow,
} from '../VirtualScrolling/updateVirtualTableVisibleRows'
import { applyRenderedTRow } from '../EditableTable3ApplyRenderedTRow'
import { reValueOrderNoColumn } from './EditableTable3OrderableColumn'

declare let tableData: { [tableName: string]: LengthAware }

const shiftDataLine = (dataSource: LengthAware, oldIndex: number, newIndex: number) => {
    const [movedItem] = dataSource.data.splice(oldIndex, 1)
    dataSource.data.splice(newIndex, 0, movedItem)

    console.log(`Row moved from ${oldIndex + 1} to ${newIndex + 1}`)
}

const reRenderRows = (
    dataSource: LengthAware,
    tableParams: TableParams,
    startIdx: number,
    endIdx: number,
) => {
    const { tableName } = tableParams
    if (startIdx > endIdx) {
        //swap startIdx and endIdx
        const temp = startIdx
        startIdx = endIdx
        endIdx = temp
    }
    const offsetStart = lastRenderedStartIdx[tableName]
    // console.log('reRenderRows start', startIdx + 1, 'end', endIdx + 1, 'offsetStart', offsetStart)
    for (let i = startIdx; i <= endIdx; i++) {
        const emptyRow = renderOneEmptyRow(tableParams, i + offsetStart)
        // console.log(`Re-rendering row ${i}`, emptyRow, i)
        //replace the actual row with the empty row
        //skip first tr as it is spacer top: tr:nth-child is 1-based not 0-based:
        const selector = `#${tableName} tbody tr:nth-child(${i + 2})`
        // console.log('selector', selector)
        const tr = document.querySelector(selector)
        // console.log('tr', tr)

        if (tr) {
            tr.replaceWith(emptyRow)
            const dataLine = dataSource.data[i + offsetStart]
            // console.log('replacing row', tr, 'with', emptyRow, 'dataLine', dataLine)
            applyRenderedTRow(tableParams, dataLine, i + offsetStart)
        }
    }
}

export const applySortableRow = (params: TableParams) => {
    const { tableName } = params
    const tableBody = document.querySelector(`#${tableName} tbody`) as HTMLElement
    Sortable.create(tableBody, {
        animation: 150,
        handle: '.drag-handle', // Use the drag handle for sorting
        onEnd: (evt) => {
            // Ignore if the indices are unchanged
            if (
                evt.oldIndex === evt.newIndex ||
                evt.oldIndex === undefined ||
                evt.newIndex === undefined
            ) {
                return
            }
            const offsetStart = lastRenderedStartIdx[tableName]
            const dataSource = tableData[tableName]
            const oldIndex = evt.oldIndex - 1 // Adjust for 1-based indexing if necessary
            const newIndex = evt.newIndex - 1 // Adjust for 1-based indexing if necessary

            if (dataSource && dataSource.data) {
                // Remove the element from the old index and insert it at the new index
                shiftDataLine(dataSource, oldIndex + offsetStart, newIndex + offsetStart)
                reRenderRows(dataSource, params, oldIndex, newIndex)
                reValueOrderNoColumn(params)
            } else {
                console.error('Data source is not valid.')
            }
        },
    })
}
