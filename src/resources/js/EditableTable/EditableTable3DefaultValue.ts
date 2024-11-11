import { TableColumn } from './Type/EditableTable3ColumnType'
import { TableParams } from './Type/EditableTable3Type'

export const ColumnNoValue: TableColumn = { title: 'No.', dataIndex: '_no_', width: 50, align: 'center' }

export const makeUpDefaultValue = ({ columns }: TableParams) => {
    return columns.map((column) => ({ ...column, width: column.width || 100 }))
}
