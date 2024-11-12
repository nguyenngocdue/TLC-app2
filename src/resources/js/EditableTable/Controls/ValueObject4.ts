import { TableColumn } from '../Type/EditableTable3ColumnType'
import { TableDataCell, TableDataLine } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3Type'

export class ValueObject4 {
    constructor(
        private params: TableParams,
        private dataLine: TableDataLine,
        private column: TableColumn,
        private index: number,
    ) {}

    render() {
        const theObject = this.dataLine[this.column.dataIndex] as unknown as TableDataCell
        const { value, cell_class, cell_href, cell_div_class, cell_onclick, cell_title } = theObject
        const classStr = cell_class ? `${cell_class}` : ''
        const hrefStr = cell_href ? `href="${cell_href}"` : ''
        const titleStr = cell_title ? `${cell_title}` : ''
        const divClassStr = cell_div_class ? `${cell_div_class}` : ''
        const onclickStr = cell_onclick ? `${cell_onclick}` : ''
        const rendered = `<a ${hrefStr}>
            <div class="${divClassStr} w-full h-full" title="${titleStr}" onclick="${onclickStr}">
                ${value}
            </div>
        </a>`
        return [rendered, classStr]
    }
}
