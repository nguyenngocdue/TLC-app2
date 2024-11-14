import { twMerge } from 'tailwind-merge'
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
        const { cellValue, params } = this.params
        const classList = twMerge(
            `${params.tableConfig.classList?.text} ${this.params.column.classList}`,
        )

        const { controlName, controlId } = this.params
        const debugStr = this.tableDebug ? `${controlName}` : ``

        const html = `<input component="text4edit" name="${controlName}" id="${controlId}" type="text" class="${classList}" value="${
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

        return { rendered: control, classStr: '' }
    }
}
