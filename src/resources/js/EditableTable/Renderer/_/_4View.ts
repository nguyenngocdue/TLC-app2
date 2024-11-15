import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class Text4View extends Renderer4View {
    render(): TableRenderedValueObject {
        return {
            rendered: 'Text4 View',
            classStr: this.column.classList || '',
        }
    }
}
