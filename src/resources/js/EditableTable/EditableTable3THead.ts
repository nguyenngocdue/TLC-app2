import { TableColumn } from './EditableTable3ColumnType'
import { TableDataLine } from './EditableTable3DataLineType'

export const makeThead = ({ dataSource, columns }: { dataSource: TableDataLine[]; columns: TableColumn[] }) => {
    const renderHeader = () => {
        const result = columns.map((column) => {
            return `<th>${column.dataIndex}</th>`
        })
        return result
    }

    return renderHeader().join('')
}
