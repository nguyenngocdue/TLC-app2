import { TableColumn } from './EditableTable3ColumnType'
import { TableConfig } from './EditableTable3ConfigType'
import { LengthAware } from './EditableTable3DataLineType'

export interface TableParams {
    tableName: string
    tableConfig: TableConfig
    columns: TableColumn[]
    dataSource: LengthAware
}
