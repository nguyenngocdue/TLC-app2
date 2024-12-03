import { LengthAware, TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'
import { applyRenderedTRow } from './EditableTable3ApplyRenderedTRow'

declare let tableData: { [tableName: string]: LengthAware }

export const applyRenderedTbody = (params: TableParams) => {
    const dataSource = tableData[params.tableName]
    if (!dataSource.data) return ''

    dataSource.data.forEach((row: TableDataLine, rowIndex: number) => {
        applyRenderedTRow(params, row, rowIndex)
    })
}
