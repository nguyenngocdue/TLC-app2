import { TableColumn } from '../Type/EditableTable3ColumnType'
import { TableConfig } from '../Type/EditableTable3ConfigType'
import {
    TableCellType,
    TableDataLine,
    TableRenderedValueObject,
    TableRendererParams,
} from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'

export abstract class Renderer4View {
    protected tableDebug = false
    protected cellValue: TableCellType
    protected column: TableColumn
    protected tableName: string
    protected dataIndex: number | string
    protected rowIndex: number
    protected dataLine: TableDataLine
    protected controlId: string
    protected tableConfig: TableConfig
    protected tableParams: TableParams
    protected customRenderFn: (() => TableRenderedValueObject) | undefined

    protected tdClass: string
    protected divClass: string

    constructor(private params: TableRendererParams) {
        this.cellValue = this.params.cellValue
        this.column = this.params.column
        this.tableName = this.params.params.tableName
        this.dataIndex = this.params.column.dataIndex
        this.rowIndex = this.params.rowIndex
        this.dataLine = this.params.dataLine
        this.controlId = this.params.controlId
        this.tableConfig = this.params.params.tableConfig
        this.tableParams = this.params.params
        this.customRenderFn = this.params.customRenderFn

        this.tdClass = ''
        this.divClass = ''
    }
    abstract render(data: any): TableRenderedValueObject
    applyPostRenderScript(): void {}
    applyOnMouseMoveScript(): void {}
    protected getTableRendererParams(): TableRendererParams {
        const result: TableRendererParams = {
            controlId: this.controlId,
            cellValue: this.cellValue,
            params: this.tableParams,
            dataLine: this.dataLine,
            column: this.column,
            rowIndex: this.rowIndex,
        }

        return result
    }
}
