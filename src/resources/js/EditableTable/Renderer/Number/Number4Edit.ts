import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'

export class Number4Edit {
    private tableDebug = false

    constructor(private params: TableRendererParams) {
        // this.tableDebug = this.params.params.tableConfig.tableDebug || false
    }

    control() {
        const { cellValue } = this.params
        const { tableName } = this.params.params
        const { dataIndex } = this.params.column
        const { rowIndex } = this.params
        const classList = this.params.params.tableConfig.classList?.text

        const name = `${tableName}[${dataIndex}][${rowIndex}]`
        const debugStr = this.tableDebug ? `${name}` : ``

        const html = `<input component="text4edit" type="number" class="${classList}" value="${
            cellValue || ''
        }" />`
        return `
        ${html}
        ${debugStr}
        `
    }

    render(): TableRenderedValueObject {
        const { cellValue } = this.params
        const control = this.control()

        return { rendered: control }
    }
}
