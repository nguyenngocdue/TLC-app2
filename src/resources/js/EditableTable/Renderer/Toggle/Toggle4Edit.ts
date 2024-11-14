import { TableRendererParams } from '../../Type/EditableTable3DataLineType'

export class Toggle4Edit {
    constructor(private params: TableRendererParams) {}

    control() {
        const { controlName, controlId, params, cellValue } = this.params
        const classList = params.tableConfig.classList?.toggle
        const checked = !!cellValue ? 'checked' : ''
        return `<div class="flex justify-center">
            <label for="${controlId}" class="inline-flex relative items-center cursor-pointer">
                <input type="checkbox" name="${controlName}" id="${controlId}" class="sr-only peer" ${checked} value="true">
                <div class="${classList}"></div>
            </label>
        </div>`
    }

    render() {
        const control = this.control()
        return { rendered: control, classStr: this.params.column.classList || '' }
    }
}
