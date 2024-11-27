import { twMerge } from 'tailwind-merge'
import { makeEmptyTd } from './EditableTable3TBodyTDEmpty'
import { TableColumn } from './Type/EditableTable3ColumnType'
import { LengthAware } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

declare let tableColumns: { [tableName: string]: TableColumn[] }
declare let tableData: { [tableName: string]: LengthAware }

export class TbodyTr {
    constructor(
        private params: TableParams,
        // private row: TableDataLine,
        private rowIndex: number,
    ) {}

    private renderRow = (tableName: string, columns: TableColumn[]) => {
        const firstFixedRightIndex = columns.findIndex((column) => column.fixed === 'right')
        return columns
            .map((column, cix) =>
                makeEmptyTd(tableName, column, this.rowIndex, cix, firstFixedRightIndex),
            )
            .join('')
    }

    render() {
        const { tableName } = this.params
        const dataSource = tableData[tableName]
        const dataLine = dataSource.data[this.rowIndex]

        const bg0 = dataLine['DESTROY_THIS_LINE'] ? 'bg-red-600' : ''
        const bg1 = dataLine['NEW_INSERTED_LINE'] ? 'bg-green-600' : ''

        const tr = document.createElement('tr')
        tr.id = `${tableName}__${this.rowIndex}`
        tr.className = twMerge('__xyz__', bg0, bg1)
        tr.innerHTML = this.renderRow(tableName, tableColumns[tableName])

        return tr
    }
}
