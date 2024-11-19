import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class ActionCheckbox extends Renderer4View {
    render(): TableRenderedValueObject {
        const { tableConfig } = this
        const classList = tableConfig.classList?.toggle_checkbox

        return {
            rendered: `<input type="checkbox" class="${classList}" />`,
            tdClass: `text-center`,
            divClass: `mx-auto`,
            divStyle: { width: '30px' },
        }
    }
}
