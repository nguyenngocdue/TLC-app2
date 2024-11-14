import { twMerge } from 'tailwind-merge'
import { TableColumnNumber } from '../../Type/EditableTable3ColumnType'
import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'

export class Number4View {
    constructor(private params: TableRendererParams) {}

    render(): TableRenderedValueObject {
        const { cellValue } = this.params
        const column = this.params.column as TableColumnNumber
        const { decimalPlaces } = column.rendererAttrs || {}
        const value = cellValue ? ((cellValue as unknown as number) * 1).toFixed(decimalPlaces) : ''
        const classList = `text-right`

        return {
            rendered: value,
            classStr: twMerge(classList, this.params.column.classList),
        }
    }
}
