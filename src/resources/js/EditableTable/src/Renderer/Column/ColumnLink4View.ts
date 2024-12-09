import { DataSourceItem } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'
import { TableColumnColumn } from '../../Type/EditableTable3ColumnType'
import { renderColumn4 } from './Shared'

export class ColumnLink4View extends Renderer4View {
    protected tdClass: string = `whitespace-nowrap`
    control() {
        const column = this.column as TableColumnColumn

        const allowOpen = true
        return renderColumn4(column, this.cellValue as unknown as DataSourceItem, allowOpen)
    }
}
