import { smartTypeOf } from '../EditableTable3Str'
import { TableColumn } from '../Type/EditableTable3ColumnType'
import {
    TableValueObjectType,
    TableDataLine,
    TableCellType,
    TableRenderedValueObject,
} from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'

export class ValueObject4 {
    constructor(
        private cellValue: TableCellType,
        private params: TableParams,
        private dataLine: TableDataLine,
        private column: TableColumn,
        private index: number,
    ) {}

    render(): TableRenderedValueObject {
        if (smartTypeOf(this.cellValue) == 'number') return { rendered: this.cellValue.toString() }
        if (smartTypeOf(this.cellValue) == 'string') return { rendered: this.cellValue.toString() }
        if (smartTypeOf(this.cellValue) == 'boolean') return { rendered: this.cellValue.toString() }
        if (smartTypeOf(this.cellValue) == 'null') return { rendered: '' }
        if (smartTypeOf(this.cellValue) == 'undefined') return { rendered: '' }

        const theObject = this.cellValue as unknown as TableValueObjectType

        const { value, cell_class, cell_href, cell_div_class, cell_onclick, cell_title } = theObject
        const classStr = cell_class ? `${cell_class}` : ''
        const hrefStr = cell_href ? `href="${cell_href}"` : ''
        const titleStr = cell_title ? `${cell_title}` : ''
        const divClassStr = cell_div_class ? `${cell_div_class}` : 'p-2'
        const onclickStr = cell_onclick ? `${cell_onclick}` : ''

        const rendered = `<a ${hrefStr}>
            <div class="${divClassStr} w-full h-full" title="${titleStr}" onclick="${onclickStr}">
                ${value ? value : ''}
            </div>
        </a>`
        return { rendered, classStr }
    }
}
