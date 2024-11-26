import { applyFixedColumnWidth } from '../FixedColumn/EditableTable3FixedColumn'
import { reValueOrderNoColumn } from '../SortableRow/EditableTable3OrderableColumn'
import { applySortableRow } from '../SortableRow/EditableTable3SortableRows'
import { TableColumn } from '../Type/EditableTable3ColumnType'
import { LengthAware } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'
import { renderRows } from './updateVirtualTableVisibleRows'

declare let tableData: { [tableName: string]: LengthAware }
declare let tableColumns: { [tableName: string]: TableColumn[] }

export const applyVirtualStatic = (params: TableParams) => {
    const { tableName, tableConfig } = params
    const columns = tableColumns[tableName]

    const dataSource = tableData[tableName]
    const indices = { startIdx: 0, endIdx: dataSource.data.length }
    renderRows(dataSource.data, indices, params, dataSource.data.length, 'bottom')
    if (tableConfig.orderable) {
        applySortableRow(params)
        reValueOrderNoColumn(params)
    }
    applyFixedColumnWidth(tableName, columns)
}
