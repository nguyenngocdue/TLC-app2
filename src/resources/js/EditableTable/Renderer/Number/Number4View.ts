import { TableColumnNumber } from '../../Type/EditableTable3ColumnType'
import { Renderer4View } from '../Renderer4View'

export class Number4View extends Renderer4View {
    protected tdClass: string = `text-right`
    control() {
        const { cellValue } = this
        const column = this.column as TableColumnNumber
        const { decimalPlaces } = column.rendererAttrs || {}
        const value = cellValue ? ((cellValue as unknown as number) * 1).toFixed(decimalPlaces) : ''

        return value
    }
}
