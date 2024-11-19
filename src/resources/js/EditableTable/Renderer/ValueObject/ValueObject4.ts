import { smartTypeOf } from '../../Function/Functions'
import {
    TableValueObjectType,
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'

export class ValueObject4 {
    constructor(private params: TableRendererParams) {}

    render(): TableRenderedValueObject {
        const { cellValue, column } = this.params
        if (smartTypeOf(cellValue) == 'number')
            return { rendered: cellValue.toString(), tdClass: 'p-2', applyPostScript: () => {} }
        if (smartTypeOf(cellValue) == 'string')
            return { rendered: cellValue.toString(), tdClass: 'p-2', applyPostScript: () => {} }
        if (smartTypeOf(cellValue) == 'boolean')
            return { rendered: cellValue.toString(), tdClass: 'p-2', applyPostScript: () => {} }

        if (smartTypeOf(cellValue) == 'null')
            return {
                rendered: '',
                tdClass: this.params.column.classList || '',
                applyPostScript: () => {},
            }
        if (smartTypeOf(cellValue) == 'undefined')
            return {
                rendered: '',
                tdClass: this.params.column.classList || '',
                applyPostScript: () => {},
            }

        const theObject = cellValue as unknown as TableValueObjectType
        if (!theObject) {
            // console.log(`ValueObject4 theObject is null: ${column.dataIndex}`, cellValue)
            return { rendered: '', tdClass: 'bg-gray-50', applyPostScript: () => {} }
        }

        const { value, cell_class, cell_href, cell_div_class, cell_onclick, cell_title } = theObject
        const classStr = cell_class ? `${cell_class}` : ''
        const hrefStr = cell_href ? `href='${cell_href}'` : ''
        const titleStr = cell_title ? `${cell_title}` : ''
        const divClassStr = cell_div_class ? `${cell_div_class}` : 'p-1 p-1-ValueObject4'
        const onclickStr = cell_onclick ? `${cell_onclick}` : ''

        const rendered = `<a ${hrefStr} class="min-w-10 min-h-5 h-6 my-0.5 p-0.5 inline-block ">
            <span component="ValueObject4" class="${divClassStr} rounded p-0.5" title="${titleStr}" onclick="${onclickStr}">
                ${value ? value : ''}
            </span>
        </a>`
        return { rendered, tdClass: classStr, applyPostScript: () => {} }
    }
}
