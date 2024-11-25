import { applyFixedColumnWidth } from '../FixedColumn/EditableTable3FixedColumn'
import { applySortableRow } from '../SortableRow/EditableTable3SortableRows'
import { LengthAware } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'
import { renderRows } from './updateVirtualTableVisibleRows'

declare let tableData: { [tableName: string]: LengthAware }

export const applyVirtualStatic = (params: TableParams) => {
    const { tableName, tableConfig, columns } = params

    const dataSource = tableData[tableName]
    const indices = { startIdx: 0, endIdx: dataSource.data.length }
    renderRows(dataSource.data, indices, params, dataSource.data.length, 'bottom')
    if (tableConfig.orderable) applySortableRow(params)
    applyFixedColumnWidth(tableName, columns)
}
