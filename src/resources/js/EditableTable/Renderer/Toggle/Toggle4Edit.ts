import { Renderer4Edit } from '../Renderer4Edit'

export class Toggle4Edit extends Renderer4Edit {
    control() {
        const { controlName, controlId, tableConfig, cellValue } = this
        const classList = tableConfig.classList?.toggle
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
        return {
            rendered: control,
            classStr: this.column.classList || '',
        }
    }
}
