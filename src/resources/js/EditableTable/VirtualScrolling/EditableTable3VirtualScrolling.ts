import { LengthAware } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'
// import { tdOnMouseMove } from './tdOnMouseMove'
import { updateVisibleRows } from './updateVirtualTableVisibleRows'

declare let tableData: { [tableName: string]: LengthAware }

export const applyVirtualScrolling = (params: TableParams) => {
    const virtualTable = document.querySelector(`#${params.tableName} table`) as HTMLTableElement
    // console.log('virtualTable', divId, virtualTable)
    if (virtualTable) {
        const dataSource = tableData[params.tableName]
        const tableContainer = virtualTable.parentElement as HTMLElement
        tableContainer.addEventListener('scroll', () =>
            updateVisibleRows(virtualTable, dataSource, params),
        )

        // Initial render
        updateVisibleRows(virtualTable, dataSource, params, true)
    }
}
