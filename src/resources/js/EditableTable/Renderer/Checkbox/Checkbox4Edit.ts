import { twMerge } from 'tailwind-merge'
import { TableRendererParams } from '../../Type/EditableTable3DataLineType'

export class Checkbox4Edit {
    constructor(private params: TableRendererParams) {}

    control() {
        const { controlName, controlId, params, cellValue } = this.params
        const classList = params.tableConfig.classList?.toggle_checkbox
        const checked = !!cellValue ? 'checked' : ''
        return `<input name="${controlName}" id="${controlId}" type="checkbox" class="${classList}" ${checked} value="true"/>`
    }

    render() {
        const control = this.control()
        const classStr = twMerge(`text-center`, this.params.column.classList)
        return { rendered: control, classStr }
    }
}
