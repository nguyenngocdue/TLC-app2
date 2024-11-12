import { TableColumn } from './EditableTable3ColumnType'
import { TableConfig } from './EditableTable3ConfigType'
import { LengthAware, TableDataLine } from './EditableTable3DataLineType'

export interface TableParams {
    tableName: string
    tableConfig: TableConfig
    columns: TableColumn[]
    dataSource: LengthAware
    dataHeader: TableDataLine
}
