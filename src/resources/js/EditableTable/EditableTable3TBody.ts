import { TableColumn } from './EditableTable3ColumnType'
import { TableDataLine } from './EditableTable3DataLineType'

export const makeTbody = ({ dataSource, columns }: { dataSource: TableDataLine[]; columns: TableColumn[] }) => {
    const renderRow = (row: TableDataLine) => {
        return columns
            .map((column) => {
                return `<td>${row[column.dataIndex]}</td>`
            })
            .join('')
    }

    return dataSource.map((row) => `<tr>${renderRow(row)}</tr>`).join('')
}
