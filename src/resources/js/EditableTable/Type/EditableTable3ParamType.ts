import { TableColumn } from './EditableTable3ColumnType'
import { TableConfig } from './EditableTable3ConfigType'
import { TableDataLine } from './EditableTable3DataLineType'

export interface TableParams {
    tableName: string
    tableConfig: TableConfig
    // columns: TableColumn[]
    // dataSource: LengthAware
    dataHeader: TableDataLine

    //derived from columns
    indexedColumns: { [key: string]: TableColumn }
}
