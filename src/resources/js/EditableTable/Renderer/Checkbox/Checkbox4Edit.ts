import { Renderer4Edit } from '../Renderer4Edit'
import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'

export class Checkbox4Edit extends Renderer4Edit {
    control() {
        const { controlName, controlId, tableConfig, cellValue } = this
        const classList = tableConfig.classList?.toggle_checkbox
        const checked = !!cellValue ? 'checked' : ''
        return `<input name="${controlName}" id="${controlId}" type="checkbox" class="${classList}" ${checked} value="true"/>`
    }

    render(): TableRenderedValueObject {
        const control = this.control()
        const tdClass = `text-center`

        return { rendered: control, tdClass }
    }
}
