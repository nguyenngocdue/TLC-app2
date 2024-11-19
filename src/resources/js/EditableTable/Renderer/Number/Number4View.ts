import { twMerge } from 'tailwind-merge'
import { TableColumnNumber } from '../../Type/EditableTable3ColumnType'
import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class Number4View extends Renderer4View {
    render(): TableRenderedValueObject {
        const { cellValue } = this
        const column = this.column as TableColumnNumber
        const { decimalPlaces } = column.rendererAttrs || {}
        const value = cellValue ? ((cellValue as unknown as number) * 1).toFixed(decimalPlaces) : ''
        const classList = `text-right`

        return {
            rendered: value,
            tdClass: classList,
        }
    }
}
