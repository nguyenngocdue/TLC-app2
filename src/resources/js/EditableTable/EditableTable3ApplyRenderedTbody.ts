import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'
import { applyRenderedTRow } from './EditableTable3ApplyRenderedTRow'

export const applyRenderedTbody = (params: TableParams) => {
    const { dataSource } = params
    if (!dataSource.data) return ''

    dataSource.data.forEach((row: TableDataLine, rowIndex: number) => {
        applyRenderedTRow(params, row, rowIndex)
    })
}
