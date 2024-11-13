import {
    TableRenderedValueObject,
    TableRendererParams,
} from '../../Type/EditableTable3DataLineType'

export class Text4Edit {
    private tableDebug = false

    constructor(private params: TableRendererParams) {
        // this.tableDebug = this.params.params.tableConfig.tableDebug || false
    }

    control() {
        const { cellValue, rowIndex, params, column } = this.params
        const { tableName, tableConfig } = params
        const { dataIndex } = column
        const classList = tableConfig.classList?.text

        const name = `${tableName}[${dataIndex}][${rowIndex}]`
        const debugStr = this.tableDebug ? `${name}` : ``

        const html = `<input component="text4edit" type="text" class="${classList}" value="${
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
