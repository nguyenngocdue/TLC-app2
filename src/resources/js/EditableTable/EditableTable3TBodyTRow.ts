import { makeTbodyTdEmpty } from './EditableTable3TBodyTDEmpty'
import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export class TbodyTr {
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

        return dataSource.data.map((row, rowIndex) => {
            const tr = document.createElement('tr')
            tr.id = `${tableName}__${rowIndex}`
            tr.className = '__xyz__'
            tr.innerHTML = renderRow(row, rowIndex)

            // Attach the onmousemove handler programmatically
            tr.addEventListener('mousemove', (e) => this.onmousemove(e, row, rowIndex))
            tr.addEventListener('mouseout', (e) => this.onmouseout(e, row, rowIndex))

            return tr
        })
    }
}
