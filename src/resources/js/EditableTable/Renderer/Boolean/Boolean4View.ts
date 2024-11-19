import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class Boolean4View extends Renderer4View {
    render(): TableRenderedValueObject {
        const { cellValue } = this
        const value = cellValue ? `<i class="fas fa-circle-check text-green-500 text-lg"></i>` : ``
        return { rendered: value, tdClass: 'text-center' }
    }
}
