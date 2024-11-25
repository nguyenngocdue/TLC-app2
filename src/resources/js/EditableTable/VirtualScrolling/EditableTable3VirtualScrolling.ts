import { TableParams } from '../Type/EditableTable3ParamType'
// import { tdOnMouseMove } from './tdOnMouseMove'
import { updateVisibleRows } from './updateVirtualTableVisibleRows'

export const applyVirtualScrolling = (params: TableParams) => {
    const virtualTable = document.querySelector(`#${params.tableName} table`) as HTMLTableElement
    // console.log('virtualTable', divId, virtualTable)
    if (virtualTable) {
        const tableContainer = virtualTable.parentElement as HTMLElement
        tableContainer.addEventListener('scroll', () =>
            updateVisibleRows(virtualTable, params.dataSource, params),
        )

        // Initial render
        updateVisibleRows(virtualTable, params.dataSource, params, true)
    }
}
