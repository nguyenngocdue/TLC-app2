import { TableColumn } from './Type/EditableTable3ColumnType'
import { TableParams } from './Type/EditableTable3Type'

export const ColumnNoValue: TableColumn = { title: 'No.', dataIndex: '_no_', renderer: 'no.', width: 50, align: 'center' }

export const makeUpDefaultValue = ({ columns }: TableParams) => {
    return columns.map((column) => ({ ...column, width: column.width || 100 }))
}

export const convertArrayToLengthAware = (dataSource: any[]) => {
    return {
        current_page: 1,
        data: dataSource,
        first_page_url: '',
        from: 1,
        last_page: 1,
        last_page_url: '',
        links: [],
        next_page_url: null,
        path: '',
        per_page: 15,
        prev_page_url: null,
        to: 1,
        total: dataSource.length,
    }
}
