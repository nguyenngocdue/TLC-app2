import { twMerge } from 'tailwind-merge'
import { Renderer4Edit } from '../Renderer4Edit'

export class Checkbox4Edit extends Renderer4Edit {
    control() {
        const { controlName, controlId, tableConfig, cellValue } = this
        const classList = tableConfig.classList?.toggle_checkbox
        const checked = !!cellValue ? 'checked' : ''
        return `<input name="${controlName}" id="${controlId}" type="checkbox" class="${classList}" ${checked} value="true"/>`
    }

    render() {
        const control = this.control()
        const classStr = twMerge(`text-center`, this.column.classList)

        return { rendered: control, classStr }
    }
}
