import { makeTbodyTdEmpty } from './EditableTable3TBodyTDEmpty'
import { TableColumn } from './Type/EditableTable3ColumnType'
import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export class TbodyTr {
    constructor(
        private params: TableParams,
        private row: TableDataLine,
        private rowIndex: number,
    ) {}

    // onmousemove = (e: MouseEvent) => {
    //     // applyRenderedTRow(this.params, row, rowIndex)
    //     console.log('onmousemove', this.row)
    // }

    // onmouseout = (e: MouseEvent) => {
    //     // applyRenderedTRow(this.params, row, rowIndex)
    //     console.log('onmouseout', this.row)
    // }

    private renderRow = (tableName: string, columns: TableColumn[]) => {
        const firstFixedRightIndex = columns.findIndex((column) => column.fixed === 'right')
        return columns
            .map((column, columnIndex) => {
                return makeTbodyTdEmpty(
                    tableName,
                    column,
                    this.rowIndex,
                    columnIndex,
                    firstFixedRightIndex,
                )
            })
            .join('')
    }
    render() {
        const { columns, tableName } = this.params

        const tr = document.createElement('tr')
        tr.id = `${tableName}__${this.rowIndex}`
        tr.className = '__xyz__'
        tr.innerHTML = this.renderRow(tableName, columns)

        // Attach the onmousemove handler programmatically
        //Does not work when applying virtual scrolling, using TBODY instead
        // tr.addEventListener('mousemove', (e) => this.onmousemove(e))
        // tr.addEventListener('mouseout', (e) => this.onmouseout(e))

        // console.log('tr', tr)

        return tr
    }
}
