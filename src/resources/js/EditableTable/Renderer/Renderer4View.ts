import { TableColumn } from '../Type/EditableTable3ColumnType'
import { TableConfig } from '../Type/EditableTable3ConfigType'
import {
    TableCellType,
    TableDataLine,
    TableRenderedValueObject,
    TableRendererParams,
} from '../Type/EditableTable3DataLineType'

export abstract class Renderer4View {
    protected tableDebug = false
    protected cellValue: TableCellType
    protected column: TableColumn
    protected tableName: string
    protected dataIndex: number | string
    protected rowIndex: number
    protected dataLine: TableDataLine
    protected controlName: string
    protected controlId: string
    protected tableConfig: TableConfig
    constructor(private params: TableRendererParams) {
        this.cellValue = this.params.cellValue
        this.column = this.params.column
        this.tableName = this.params.params.tableName
        this.dataIndex = this.params.column.dataIndex
        this.rowIndex = this.params.rowIndex
        this.dataLine = this.params.dataLine
        this.controlName = this.params.controlName
        this.controlId = this.params.controlId
        this.tableConfig = this.params.params.tableConfig
    }
    abstract render(data: any): TableRenderedValueObject
}
