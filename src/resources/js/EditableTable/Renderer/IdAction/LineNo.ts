import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class LineNo extends Renderer4View {
    render(): TableRenderedValueObject {
        const rowIndex = this.rowIndex

        return {
            rendered: `${rowIndex + 1}`,
            tdClass: `text-center`,
        }
    }
}
