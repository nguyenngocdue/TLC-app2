import { makeTbodyTdEmpty } from './EditableTable3TBodyTDEmpty'
import { TbodyTr } from './EditableTable3TBodyTRow'
import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export class TbodyTrs {
    constructor(private params: TableParams) {}

    onmousemove = (e: MouseEvent, row: TableDataLine, rowIndex: number) => {
        // applyRenderedTRow(this.params, row, rowIndex)
    }

    onmouseout = (e: MouseEvent, row: TableDataLine, rowIndex: number) => {
        // applyRenderedTRow(this.params, row, rowIndex)
    }

    render() {
        const { dataSource, columns, tableName } = this.params
        if (!dataSource.data) return []
        const firstFixedRightIndex = columns.findIndex((column) => column.fixed === 'right')
        const renderRow = (_: TableDataLine, rowIndex: number) => {
            return columns
                .map((column, columnIndex) => {
                    return makeTbodyTdEmpty(
                        tableName,
                        column,
                        rowIndex,
                        columnIndex,
                        firstFixedRightIndex,
                    )
                })
                .join('')
        }

        const result = dataSource.data.map((row, rowIndex) =>
            new TbodyTr(this.params, row, rowIndex).render(),
        )
        return result
    }
}
