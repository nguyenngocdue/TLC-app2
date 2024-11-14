import { twMerge } from 'tailwind-merge'
import { TableColumnNumber } from '../../Type/EditableTable3ColumnType'
import { TableRendererParams } from '../../Type/EditableTable3DataLineType'

export class Dropdown4Edit {
    private tableDebug = false

    constructor(private params: TableRendererParams) {
        // this.tableDebug = this.params.params.tableConfig.tableDebug || false
    }

    control() {
        const { cellValue } = this.params
        const { tableName } = this.params.params
        const { dataIndex } = this.params.column
        const { rowIndex } = this.params
        const column = this.params.column as TableColumnNumber
        const { decimalPlaces = 0 } = column.rendererAttrs || {}
        const classList = twMerge(`text-right`, this.params.params.tableConfig.classList?.text)

        const name = `${tableName}[${dataIndex}][${rowIndex}]`
        const debugStr = this.tableDebug ? `${name}` : ``
        const value = cellValue ? ((cellValue as unknown as number) * 1).toFixed(decimalPlaces) : ''
        const step = Math.pow(10, -decimalPlaces)
        // console.log('step', step, decimalPlaces)

        const html = `<input component="text4edit" step="${step}" type="number" class="${classList}" value="${value}" />`
        return `
        ${html}
        ${debugStr}
        `
    }

    render() {
        const control = this.control()
        return { rendered: control, classStr: this.params.column.classList || '' }
    }
}
